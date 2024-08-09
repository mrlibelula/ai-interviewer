<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // bonus XP time thresholds in seconds
        // define('HIGH_THRESHOLD_SECONDS', 300);
        // define('MEDIUM_THRESHOLD_SECONDS', 600);
    }
}
