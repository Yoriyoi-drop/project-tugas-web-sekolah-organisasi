<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAbility
{
    public function handle(Request $request, Closure $next, string $ability)
    {
        if (!auth()->check() || !auth()->user()->hasAbility($ability)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
