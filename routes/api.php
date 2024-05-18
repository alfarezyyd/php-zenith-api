<?php

  use App\Http\Controllers\AddressController;
  use App\Http\Controllers\CartController;
  use App\Http\Controllers\CategoryController;
  use App\Http\Controllers\ExpeditionController;
  use App\Http\Controllers\MidtransController;
  use App\Http\Controllers\OrderController;
  use App\Http\Controllers\ProductController;
  use App\Http\Controllers\StoreController;
  use App\Http\Controllers\UserProfileController;
  use App\Http\Controllers\WishlistController;
  use Illuminate\Support\Facades\Route;


  Route::middleware(['auth:sanctum', 'verified'])->group(function () {
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
      Route::get('find', [StoreController::class, 'findByUser']);
      Route::get('/{storeSlug}', [StoreController::class, 'show']);
      Route::post('', [StoreController::class, 'store']);
      Route::put('/{storeId}', [StoreController::class, 'update']);
      Route::delete('/{storeId}', [StoreController::class, 'destroy']);
    });

    Route::prefix('carts')->group(function () {
      Route::get('', [CartController::class, 'index']);
      Route::post('/{productId}', [CartController::class, 'attachProductIntoCart']);
      Route::put('/{cartId}', [CartController::class, 'update']);
      Route::delete('/{cartId}', [CartController::class, 'destroy']);
    });

    Route::prefix('products')->group(function () {
      Route::get('', [ProductController::class, 'index']);
      Route::get('/{categorySlug}', [ProductController::class, 'indexByCategory']);
      Route::get('/{storeSlug}/{categorySlug}', [ProductController::class, 'show']);
      Route::post('/{storeSlug}', [ProductController::class, 'store']);
      Route::put('/{productId}', [ProductController::class, 'update']);
      Route::delete('/{storeSlug}/{productId}', [ProductController::class, 'destroy']);
    });

    Route::prefix('wishlists')->group(function () {
      Route::get('', [WishlistController::class, 'index']);
      Route::get('/', [WishlistController::class, 'indexByCategory']);
      Route::get('/{wishlistSlug}', [WishlistController::class, 'show']);
      Route::post('/', [WishlistController::class, 'store']);
      Route::put('/{wishlistId}', [WishlistController::class, 'update']);
      Route::delete('/{wishlistId}', [WishlistController::class, 'destroy']);
      Route::get('/{wishlistId}/{productId}', [WishlistController::class, 'attachProductIntoWishlist']);
      Route::delete('/{wishlistId}/{productId}', [WishlistController::class, 'detachProductFromWishlist']);
    });

    Route::prefix('orders')->group(function () {
      Route::get('', [OrderController::class, 'index']);
    });

    Route::prefix('user-profiles')->group(function () {
      Route::get('', [UserProfileController::class, 'index']);
      Route::get('', [UserProfileController::class, 'show']);
      Route::post('', [UserProfileController::class, 'store']);
      Route::put('/{userProfileId}', [UserProfileController::class, 'update']);
      Route::delete('/{userProfileId}', [UserProfileController::class, 'destroy']);
    });

    Route::prefix('checkout')->group(function () {
      Route::post('', [MidtransController::class, 'initPayment']);
    });
  });


