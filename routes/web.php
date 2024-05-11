<?php

  use App\Http\Controllers\Auth\SocialiteController;
  use Illuminate\Support\Facades\Route;

  Route::get('/', function () {
    return view('welcome');
  });

  /**
   * socialite auth
   */
  Route::prefix('auth')->group(function () {
    Route::get('/{provider}', [SocialiteController::class, 'redirectToProvider']);
    Route::get('/{provider}/callback', [SocialiteController::class, 'handleProvideCallback']);
  });
