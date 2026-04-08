<?php

namespace App\Providers;

use App\Models\Organization;
use App\Observers\OrganizationObserver;
use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register observers
        Organization::observe(OrganizationObserver::class);
        
        // Configure cache for production
        if (app()->environment('production')) {
            config([
                'cache.default' => 'redis',
                'cache.prefix' => config('cache.prefix', 'app_cache'),
            ]);
        }
    }
}
