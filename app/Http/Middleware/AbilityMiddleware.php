<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AbilityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $ability): Response
    {
        if (!$request->user() || !$request->user()->hasAbility($ability)) {
            abort(403, 'You do not have the required permission to access this resource.');
        }

        return $next($request);
    }
}
