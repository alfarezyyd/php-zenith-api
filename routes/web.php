<?php

  use App\Http\Controllers\Auth\SocialiteController;
  use Illuminate\Support\Facades\Route;

  Route::get('/', function () {
    return view('welcome');
  });

  /**
   * socialite auth
   */
  Route::middleware(['web'])->group(function () {
    Route::get('/auth/{provider}', [SocialiteController::class, 'redirectToProvider']);
    Route::get('/auth/{provider}/callback', [SocialiteController::class, 'handleProvideCallback']);
  });
