<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            // Redirect guest users to login
            return redirect()->route('login')->with('error', 'Access denied. Admin privileges required.');
        }

        $user = Auth::user();
        
        // Check if user is verified (if email verification is required)
        if (! $user->hasVerifiedEmail()) {
            // Redirect unverified users to verification notice
            return redirect()->route('verification.notice');
        }

        // Check if user is admin
        if (! $user->isAdmin()) {
            // Return 403 for authenticated non-admin users as expected by tests
            abort(403, 'Access denied. Admin privileges required.');
        }

        return $next($request);
    }
}
