<?php

  namespace App\Http\Controllers;

  use App\Models\Cart;
  use App\Models\Product;
  use App\Payloads\WebResponsePayload;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Auth;

  class CartController extends Controller
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
    public function store(Request $request)
    {

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

    public function attachProductIntoCart(int $cartId, int $productId)
    {
      $productModel = Product::query()->findOrFail($productId);
      $cartModel = Cart::query()->findOrFail($cartId)->where('user_id', Auth::id());
      $cartModel->products()->attach($productModel);
      return response()
        ->json((new WebResponsePayload("Product attached successfully"))
          ->getJsonResource())->setStatusCode(200);
    }

    public function detachProductFromCart(int $cartId, int $productId)
    {
      $productModel = Product::query()->findOrFail($productId);
      $cartModel = Cart::query()->findOrFail($cartId)->where('user_id', Auth::id());
      $cartModel->products()->detach($productModel);
      return response()
        ->json((new WebResponsePayload("Product detached successfully"))
          ->getJsonResource())->setStatusCode(200);
    }
  }
