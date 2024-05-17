<?php

  namespace App\Http\Controllers;

  use App\Http\Requests\PaymentRequest;
  use App\Http\Resources\MidtransResponse;
  use App\Models\Product;
  use App\Payloads\WebResponsePayload;
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Str;
  use Midtrans\Config;
  use Midtrans\Snap;

  class MidtransController extends Controller
  {
    public function initPayment(PaymentRequest $paymentRequest)
    {
      Config::$serverKey = env('MIDTRANS_SERVER_KEY');
      Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
      Config::$isSanitized = env('MIDTRANS_IS_SANITIZED');
      Config::$is3ds = env('MIDTRANS_IS_3DS');

      $validatedPaymentRequest = $paymentRequest->validated();
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
  }
