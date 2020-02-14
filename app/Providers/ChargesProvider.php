<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ChargesProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Pricing', function () {
            return new \App\Pricing\Pricing;
        });
    }
}
