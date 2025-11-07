<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        $this->routes(function () {
            // Load API routes under /api prefix
            Route::prefix('api')->middleware('api')->group(function () {
                if (file_exists(base_path('routes/api.php'))) {
                    require base_path('routes/api.php');
                }
            });

            // Load web routes
            if (file_exists(base_path('routes/web.php'))) {
                Route::middleware('web')->group(base_path('routes/web.php'));
            }

            // Load cached routes
            if (file_exists(base_path('routes/cache.php'))) {
                Route::middleware('web')->group(base_path('routes/cache.php'));
            }
        });
    }
}
