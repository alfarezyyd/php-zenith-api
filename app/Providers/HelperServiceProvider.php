<?php

  namespace App\Providers;

  use App\Helpers\CommonHelper;
  use Illuminate\Support\ServiceProvider;

  class HelperServiceProvider extends ServiceProvider
  {
    public function register(): void
    {
      $this->app->singleton('validateOperationState', function () {
        return new CommonHelper();
      });
    }
  }
