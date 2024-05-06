<?php

namespace App\Providers;

use App\Repository\Contract\SearchRepository;
use App\Repository\Implementation\ProductSearchRepository;
use Illuminate\Support\ServiceProvider;

class SearchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(SearchRepository::class, ProductSearchRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
