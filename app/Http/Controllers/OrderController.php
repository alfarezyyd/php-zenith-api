<?php

  namespace App\Http\Controllers;

  use App\Http\Requests\OrderRequest;
  use App\Models\Address;
  use App\Models\Order;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Auth;
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
      $productIds = $validatedPaymentRequest['order_payload']['product_id'];
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
