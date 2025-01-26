<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\DiscountService;

class DiscountServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(DiscountService::class, function ($app) {
            return new DiscountService();
        });
    }
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
