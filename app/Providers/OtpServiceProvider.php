<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

class OtpServiceProvider extends ServiceProvider
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
        // Rate limiter for OTP verification attempts
        RateLimiter::for('otp-verify', function ($request) {
            $key = $request->session()->get('otp_user_id', 0);
            return Limit::perMinutes(10, 5)->by($key);
        });

        // Rate limiter for OTP generation
        RateLimiter::for('otp-generate', function ($request) {
            $key = $request->session()->get('otp_user_id', 0);
            return Limit::perMinutes(30, 3)->by($key);
        });
    }
}
