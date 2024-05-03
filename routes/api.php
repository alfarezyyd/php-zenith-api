<?php

  use App\Http\Controllers\AddressController;
  use App\Http\Controllers\CategoryController;
  use App\Http\Controllers\ExpeditionController;
  use App\Http\Controllers\StoreController;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Route;

  Route::get('/user', function (Request $request) {
    return $request->user();
  })->middleware('auth:sanctum');

  Route::middleware([
      'auth:sanctum',
      config('jetstream.auth_session'),
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
        Route::post('', [AddressController::class, 'store']);
        Route::put('/{categoryId}', [AddressController::class, 'update']);
        Route::delete('/{categoryId}', [AddressController::class, 'destroy']);
      });

      Route::prefix('stores')->group(function () {
        Route::get('', [StoreController::class, 'index']);
        Route::post('', [StoreController::class, 'store']);
        Route::put('/{storeId}', [StoreController::class, 'update']);
        Route::delete('/{storeId}', [StoreController::class, 'destroy']);
      });
    });


