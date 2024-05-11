<?php

  use App\Http\Controllers\Auth\SocialiteController;
  use Illuminate\Support\Facades\Route;

  Route::get('/', function () {
    return view('welcome');
  });

  /**
   * socialite auth
   */
  Route::prefix('auth')->middleware(\App\Http\Middleware\RedirectIfAuthenticated::class)->group(function () {
    Route::get('/{provider}', [SocialiteController::class, 'redirectToProvider']);
    Route::get('/{provider}/callback', [SocialiteController::class, 'handleProvideCallback']);
  });
