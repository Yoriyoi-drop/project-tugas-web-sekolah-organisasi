<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        // API rate limiting
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Authentication rate limiting
        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        // OTP rate limiting
        RateLimiter::for('otp', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip());
        });

        // Students API specific rate limiting
        RateLimiter::for('students-api', function (Request $request) {
            return Limit::perMinute(30)->by($request->user()?->id ?: $request->ip());
        });

        // Admin API rate limiting (higher limits for admins)
        RateLimiter::for('admin-api', function (Request $request) {
            $user = $request->user();
            if ($user && $user->is_admin) {
                return Limit::perMinute(100)->by($user->id);
            }
            return Limit::perMinute(10)->by($request->ip());
        });

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
