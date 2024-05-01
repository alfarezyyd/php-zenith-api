<?php

  use App\Http\Controllers\AddressController;
  use App\Http\Controllers\CategoryController;
  use App\Http\Controllers\ExpeditionController;
  use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Route;

  Route::get('/user', function (Request $request) {
    return $request->user();
  })->middleware('auth:sanctum')->withoutMiddleware(VerifyCsrfToken::class);

  Route::resource('addresses', AddressController::class);

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
    Route::put('/{expeditionId}', [CategoryController::class, 'update']);
    Route::delete('/{expeditionId}', [CategoryController::class, 'destroy']);
    Route::get('/sync-province', [CategoryController::class, 'syncThirdPartyProvince']);
    Route::get('/sync-city', [CategoryController::class, 'syncThirdPartyCity']);
  });
