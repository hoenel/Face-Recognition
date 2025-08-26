<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class FirebaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Simplified Firebase service without complex dependencies
        $this->app->singleton('firebase.http', function ($app) {
            return new \Illuminate\Support\Facades\Http();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
