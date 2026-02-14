<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminOrRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            // Jika pengguna tidak login, arahkan ke halaman login admin
            return redirect()->route('admin.login')->with('error', 'Silakan login sebagai admin untuk mengakses halaman ini.');
        }

        $user = Auth::user();
        
        // Check if user is verified (if email verification is required)
        if (!$user->hasVerifiedEmail()) {
            // Redirect unverified users to verification notice
            return redirect()->route('verification.notice');
        }

        // Jika pengguna login tetapi bukan admin, kembalikan status 403
        if (!$user->is_admin) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        return $next($request);
    }
}