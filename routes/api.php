<?php

  use App\Http\Controllers\AddressController;
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
