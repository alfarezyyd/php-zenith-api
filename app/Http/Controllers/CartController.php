<?php

  namespace App\Http\Controllers;

  use App\Http\Resources\ProductCartResource;
  use App\Http\Resources\ProductResource;
  use App\Http\Resources\StoreResource;
  use App\Models\Product;
  use App\Models\ProductCart;
  use App\Payloads\WebResponsePayload;
  use Illuminate\Http\JsonResponse;
  use Illuminate\Http\Request;
  use Illuminate\Support\Collection;
  use Illuminate\Support\Facades\Auth;

  class CartController extends Controller
  {
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
      $cartModel = ProductCart::query()->with(['product', 'store'])->where('cart_id', Auth::user()->cart()->first()->id)->get();
      // Buat array kosong untuk menyimpan hasil kelompokkan
      $groupedProducts = [];

// Lakukan iterasi untuk setiap item pada hasil query
      foreach ($cartModel as $productCart) {
        $storeId = $productCart->store->id;

        // Jika belum ada array untuk store ini, buat satu
        if (!isset($groupedProducts[$storeId])) {
          $groupedProducts[$storeId] = [
            'store' => $productCart->store, // Informasi store
            'products' => [], // Array kosong untuk menyimpan produk
          ];
        }

        // Tambahkan produk ke dalam array products di bawah store yang sesuai
        $groupedProducts[$storeId]['products'][] = $productCart->product;
      }
      return response()
        ->json((new WebResponsePayload("Cart retrieve successfully", jsonResource: ProductCartResource::collection($groupedProducts)))
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
      $productModel = Product::query()->where('id', $productId)->firstOrFail();
      $cartModel = Auth::user()->cart()->first();
      $newProductCart = [
        'product_id' => $productId,
        'store_id' => $productModel['store_id'],
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
      // Cari product berdasarkan ID, jika tidak ditemukan akan mengembalikan 404
      $productModel = Product::query()->findOrFail($productId);

      // Ambil model Cart milik user yang sedang login
      $cartModel = Auth::user()->cart;

      if ($cartModel) {
        // Detach product dari relasi products pada model Cart
        $cartModel->products()->detach($productModel->id);

        return response()
          ->json((new WebResponsePayload("Product detached successfully"))
            ->getJsonResource())->setStatusCode(200);
      } else {
        return response()
          ->json((new WebResponsePayload("Cart not found"))
            ->getJsonResource())->setStatusCode(404);
      }
    }

    private function groupProductCartsByStore(Collection $productCarts): \Illuminate\Support\Collection
    {
      $groupedByStore = collect();

      foreach ($productCarts as $productCart) {
        $storeId = $productCart->store_id;

        if (!$groupedByStore->has($storeId)) {
          $groupedByStore->put($storeId, collect());
        }

        $groupedByStore[$storeId]->push($productCart);
      }

      return $groupedByStore;
    }

    private function formatProducts(Collection $productCarts): array
    {
      return $productCarts->map(function ($productCart) {
        return [
          'id' => $productCart->id,
          'sub_total_price' => $productCart->sub_total_price,
          'quantity' => $productCart->quantity,
          'product' => new ProductResource($productCart->product),  // Menggunakan ProductResource untuk informasi produk
        ];
      })->toArray();
    }
  }
