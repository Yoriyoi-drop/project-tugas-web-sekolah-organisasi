<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Access denied. Admin privileges required.');
        }

        // If the user is authenticated but not an admin, return 403 so ability middleware
        // can be relied upon for more specific permissions (tests expect 403 for auth users).
        if (! Auth::user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        return $next($request);
    }
}
