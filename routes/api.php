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
  Route::get('expeditions/sync-province', [ExpeditionController::class, 'syncThirdPartyProvince']);
  Route::get('expeditions/sync-city', [ExpeditionController::class, 'syncThirdPartyCity']);
