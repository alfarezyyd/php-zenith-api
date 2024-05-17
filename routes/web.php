<?php

  use App\Http\Controllers\Auth\SocialiteController;
  use Illuminate\Support\Facades\Route;

  Route::get('/', function () {
    return view('welcome');
  });

  /**
   * socialite auth
   */
  Route::prefix('auth')->middleware('redirectIfAuthenticated')->group(function () {
    Route::get('/{provider}', [SocialiteController::class, 'redirectToProvider']);
    Route::get('/{provider}/callback', [SocialiteController::class, 'handleProvideCallback']);
    Route::post('/logout', [SocialiteController::class, 'logoutHandler']);
  });
  Route::get('/update', [SocialiteController::class, 'updateLoginToken']);

