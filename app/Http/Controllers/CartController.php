<?php

  namespace App\Http\Controllers;

  use App\Http\Resources\ProductCartResource;
  use App\Models\Cart;
  use App\Models\Product;
  use App\Models\ProductCart;
  use App\Payloads\WebResponsePayload;
  use Illuminate\Http\JsonResponse;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Auth;

  class CartController extends Controller
  {
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
      $cartModel = ProductCart::query()->where('cart_id', Auth::user()->cart()->first()->id)->get();
      return response()
        ->json((new WebResponsePayload("Cart retrieve successfully", jsonResource: ProductCartResource::collection($cartModel)))
          ->getJsonResource())->setStatusCode(200);
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

    public function attachProductIntoCart(int $productId): JsonResponse
    {
      $productModel = Product::query()->findOrFail($productId);
      $cartModel = Auth::user()->cart()->first();
      $newProductCart = [
        'product_id' => $productId,
        'cart_id' => $cartModel->id,
        'sub_total_price' => $productModel['price'],
        'quantity' => 1,
      ];

      $productCart = new ProductCart($newProductCart);
      $productCart->save();
      return response()
        ->json((new WebResponsePayload("Product attached successfully"))
          ->getJsonResource())->setStatusCode(200);
    }

    public function detachProductFromCart(int $productId): JsonResponse
    {
      $productModel = Product::query()->findOrFail($productId);
      $cartModel = Auth::user()->cart();
      $cartModel->products()->detach($productModel);
      return response()
        ->json((new WebResponsePayload("Product detached successfully"))
          ->getJsonResource())->setStatusCode(200);
    }
  }
