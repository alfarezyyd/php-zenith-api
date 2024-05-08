<?php

  use App\Http\Controllers\AddressController;
  use App\Http\Controllers\CartController;
  use App\Http\Controllers\CategoryController;
  use App\Http\Controllers\ExpeditionController;
  use App\Http\Controllers\ProductController;
  use App\Http\Controllers\StoreController;
  use App\Http\Controllers\WishlistController;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Route;

  Route::get('/user', function (Request $request) {
    return $request->user();
  })->middleware('auth:sanctum');


  Route::middleware([
    'auth:sanctum',
    'verified',
  ])->group(function () {
    Route::prefix('expeditions')->group(function () {
      Route::get('', [ExpeditionController::class, 'index']);
      Route::post('', [ExpeditionController::class, 'store']);
      Route::put('/{expeditionId}', [ExpeditionController::class, 'update']);
      Route::delete('/{expeditionId}', [ExpeditionController::class, 'destroy']);
      Route::get('/sync-province', [ExpeditionController::class, 'syncThirdPartyProvince']);
      Route::get('/sync-city', [ExpeditionController::class, 'syncThirdPartyCity']);
    });

    Route::prefix('categories')->group(function () {
      Route::get('', [CategoryController::class, 'index']);
      Route::post('', [CategoryController::class, 'store']);
      Route::put('/{categoryId}', [CategoryController::class, 'update']);
      Route::delete('/{categoryId}', [CategoryController::class, 'destroy']);
    });

    Route::prefix('addresses')->group(function () {
      Route::get('', [AddressController::class, 'index']);
      Route::get('/{addressId}', [AddressController::class, 'show']);
      Route::post('', [AddressController::class, 'store']);
      Route::put('/{addressId}', [AddressController::class, 'update']);
      Route::delete('/{addressId}', [AddressController::class, 'destroy']);
    });

    Route::prefix('stores')->group(function () {
      Route::get('', [StoreController::class, 'index']);
      Route::post('', [StoreController::class, 'store']);
      Route::get('/{storeSlug}', [StoreController::class, 'show']);
      Route::put('/{storeId}', [StoreController::class, 'update']);
      Route::delete('/{storeId}', [StoreController::class, 'destroy']);
    });

    Route::prefix('carts')->group(function () {
      Route::get('', [CartController::class, 'index']);
      Route::post('', [CartController::class, 'store']);
      Route::put('/{cardId}', [CartController::class, 'update']);
      Route::delete('/{cardId}', [CartController::class, 'destroy']);
    });

    Route::prefix('products')->group(function () {
      Route::get('', [ProductController::class, 'index']);
      Route::get('/{categorySlug}', [ProductController::class, 'indexByCategory']);
      Route::get('/{storeSlug}/{categorySlug}', [ProductController::class, 'show']);
      Route::post('/{storeId}', [ProductController::class, 'store']);
      Route::put('/{productId}', [ProductController::class, 'update']);
      Route::delete('/{productId}', [ProductController::class, 'destroy']);
    });

    Route::prefix('wishlists')->group(function () {
      Route::get('', [WishlistController::class, 'index']);
      Route::get('/', [WishlistController::class, 'indexByCategory']);
      Route::get('/{wishlistSlug}', [WishlistController::class, 'show']);
      Route::post('/', [WishlistController::class, 'store']);
      Route::put('/{wishlistId}', [WishlistController::class, 'update']);
      Route::delete('/{wishlistId}', [WishlistController::class, 'destroy']);
      Route::get('/{wishtlistId}/{productId}', [WishlistController::class, 'attachProductIntoWishlist']);
      Route::get('detach/{wishtlistId}/{productId}', [WishlistController::class, 'detachProductFromWishlist']);
    });
  });


