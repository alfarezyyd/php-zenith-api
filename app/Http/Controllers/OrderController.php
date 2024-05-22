<?php

  namespace App\Http\Controllers;

  use App\Enums\OrderStatus;
  use App\Http\Requests\OrderRequest;
  use App\Http\Requests\OrderSentRequest;
  use App\Http\Resources\MidtransResponse;
  use App\Http\Resources\OrderResource;
  use App\Http\Resources\StoreOrderResource;
  use App\Http\Resources\UserOrderResource;
  use App\Models\Address;
  use App\Models\Order;
  use App\Models\ProductOrder;
  use App\Payloads\WebResponsePayload;
  use Carbon\Carbon;
  use Illuminate\Http\Exceptions\HttpResponseException;
  use Illuminate\Http\JsonResponse;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Str;
  use Midtrans\Config;
  use Midtrans\Snap;

  class OrderController extends Controller
  {
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      //
    }

    public function indexByStore(int $storeId)
    {
      $storeOrders = Order::query()->with(['user', 'expedition', 'address'])->where('store_id', $storeId)->get();
      return response()->json(
        (new WebResponsePayload('Order indexed by store succesfully', jsonResource: StoreOrderResource::collection($storeOrders)))
          ->getJsonResource())->setStatusCode(200);
    }

    public function indexByUser()
    {
      $userModel = Auth::user();
      $userOrders = $userModel->orders;
      $userOrders->load(['expedition', 'address']);
      return response()->json(
        (new WebResponsePayload('Order indexed by user succesfully', jsonResource: UserOrderResource::collection($userOrders)))
          ->getJsonResource())->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public
    function store(OrderRequest $orderRequest)
    {
      Config::$serverKey = env('MIDTRANS_SERVER_KEY');
      Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
      Config::$isSanitized = env('MIDTRANS_IS_SANITIZED');
      Config::$is3ds = env('MIDTRANS_IS_3DS');

      $validatedPaymentRequest = $orderRequest->validated();
      try {
        DB::beginTransaction();
        $orderId = Str::uuid()->toString();
        $newOrderModel = new Order([
          'total_price' => $validatedPaymentRequest['gross_amount'],
          'status' => OrderStatus::NEW,
          'address_id' => $validatedPaymentRequest['address_id'],
          'user_id' => Auth::id(),
          'id' => $orderId,
          'store_id' => $validatedPaymentRequest['store_id'],
          'expedition_id' => $validatedPaymentRequest['expedition_id']
        ]);
        $newOrderModel->save();
        $productsOrders = [];
        foreach ($validatedPaymentRequest['order_payload'] as $product) {
          $productOrder['product_id'] = $product['id'];
          $productOrder['quantity'] = $product['quantity'];
          $productOrder['order_id'] = $orderId;
          $productOrder['sub_total_price'] = $product['price'] * $product['quantity'];
          $productOrder['created_at'] = $newOrderModel->freshTimestampString();
          $productOrders[] = $productOrder;
        }
        ProductOrder::query()->insert($productOrders);
        $paymentPayload = [
          'transaction_details' => [
            'order_id' => $orderId,
            'gross_amount' => $validatedPaymentRequest['gross_amount']
          ],
          'customer_details' => [
            'first_name' => Auth::user()->profile['first_name'],
            'last_name' => Auth::user()->profile['last_name'],
            'email' => Auth::user()->profile['email'],
            'phone' => Auth::user()->profile['phone']
          ],
          'callbacks' => [
            "finish" => "http://localhost:3000/thanks"
          ]
        ];
        $responsePayload['token'] = Snap::getSnapToken($paymentPayload);
        Order::query()->where('id', $orderId)->update([
          'midtrans_token' => $responsePayload['token']
        ]);

        DB::commit();
        return response()->json(
          (new WebResponsePayload(responseMessage: $responsePayload['token'], jsonResource: new MidtransResponse($responsePayload)))
            ->getJsonResource()
        )->setStatusCode(200);
      } catch (\Exception $e) {
        DB::rollBack();
        throw new HttpResponseException(response()->json(
          (new WebResponsePayload("Something went wrong when trying to make order.", $e->getMessage()))->getJsonResource(),
        ));
      }


    }

    /**
     * Display the specified resource.
     */
    public function show(string $orderId): JsonResponse
    {
      $orderModel = Order::query()->with(['expedition', 'address', 'user'])->where('id', $orderId)->first();
      return response()->json(
        (new WebResponsePayload('Order retrieved succesfully', jsonResource: new OrderResource($orderModel)))
        ->getJsonResource()
      );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public
    function edit(string $id)
    {
      //
    }

    /**
     * Update the specified resource in storage.
     */
    public
    function update(Request $request, string $id)
    {
      //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
      //
    }

    public function updateReceiptNumber(OrderSentRequest $orderSentRequest)
    {
      $validatedOrderSentRequest = $orderSentRequest->validated();
      Order::query()->where('id', $validatedOrderSentRequest['order_id'])->update([
        'status' => OrderStatus::SENT,
        'receipt_number' => $validatedOrderSentRequest['receipt_number']
      ]);
      return response()->json(
        (new WebResponsePayload('Order successfully sent'))->getJsonResource()
      )->setStatusCode(200);
    }

    public function confirmOrderCompleted(string $orderId): JsonResponse
    {
      Order::query()->where('id', $orderId)->update([
        'status' => OrderStatus::FINISHED
      ]);
      return response()->json(
        (new WebResponsePayload('Order updated to complete'))->getJsonResource()
      )->setStatusCode(200);
    }

       public function updateRejectOrder(string $orderId): JsonResponse
    {
      Order::query()->where('id', $orderId)->update([
        'status' => OrderStatus::CANCELLED
      ]);
      return response()->json(
        (new WebResponsePayload('Order updated to reject'))->getJsonResource()
      )->setStatusCode(200);
    }
  }
