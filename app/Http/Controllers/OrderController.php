<?php

  namespace App\Http\Controllers;

  use App\Enums\OrderStatus;
  use App\Http\Requests\OrderRequest;
  use App\Models\Address;
  use App\Models\Order;
  use App\Models\ProductOrder;
  use App\Payloads\WebResponsePayload;
  use Carbon\Carbon;
  use Illuminate\Http\Exceptions\HttpResponseException;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Str;
  use Midtrans\Config;

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

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $orderRequest)
    {
      Config::$serverKey = env('MIDTRANS_SERVER_KEY');
      Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
      Config::$isSanitized = env('MIDTRANS_IS_SANITIZED');
      Config::$is3ds = env('MIDTRANS_IS_3DS');

      $validatedPaymentRequest = $orderRequest->validated();
      try {
        DB::beginTransaction();

        $newOrderModel = new Order([
          'total_price' => $validatedPaymentRequest['gross_amount'],
          'status' => OrderStatus::NEW,
          'address_id' => $validatedPaymentRequest['address_id'],
          'user_id' => Auth::id(),
          'expedition_id' => $validatedPaymentRequest['expedition_id']
        ]);
        $newOrderModel->save();
        $productsOrders = [];
        foreach ($validatedPaymentRequest['order_payload'] as $product) {
          $productOrder['product_id'] = $product['id'];
          $productOrder['quantity'] = $product['quantity'];
          $productOrder['order_id'] = $newOrderModel->id;
          $productOrder['sub_total_price'] = $product['price'] * $product['quantity'];
          $productOrder['created_at'] = $newOrderModel->freshTimestampString();
          $productOrders[] = $productOrder;
        }
        ProductOrder::query()->insert($productOrders);
        DB::commit();
      } catch (\Exception $e) {
        DB::rollBack();
        throw new HttpResponseException(response()->json(
          (new WebResponsePayload("Something went wrong when trying to make order.", 500))->getJsonResource(),
        ));
      }
      dd("commited");
      $paymentPayload = [
        'transaction_details' => [
          'order_id' => Str::uuid()->toString(),
          'gross_amount' => $validatedPaymentRequest['gross_amount']
        ],
        'customer_details' => [
          'first_name' => Auth::user()->profile['first_name'],
          'last_name' => Auth::user()->profile['last_name'],
          'email' => Auth::user()->profile['email'],
          'phone' => Auth::user()->profile['phone']
        ]
      ];
      $responsePayload['token'] = Snap::getSnapToken($paymentPayload);
      return response()->json(
        (new WebResponsePayload(responseMessage: "Midtrans token retrieve succesfully", jsonResource: new MidtransResponse($responsePayload)))
          ->getJsonResource()
      )->setStatusCode(200);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
      //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
      //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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
  }
