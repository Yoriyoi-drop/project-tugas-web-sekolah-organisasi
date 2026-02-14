<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\SecurityService;
use Symfony\Component\HttpFoundation\Response;

class CheckAbility
{
    public function handle(Request $request, Closure $next, string $ability): Response
    {
        \Log::info('CheckAbility middleware called', [
            'ability' => $ability,
            'user_id' => auth()->id(),
            'has_ability' => auth()->check() ? auth()->user()->hasAbility($ability) : false
        ]);

        if (!auth()->check() || !auth()->user()->hasAbility($ability)) {
            // Log unauthorized access attempt
            SecurityService::logActivity('unauthorized_access_attempt', [
                'ability' => $ability,
                'user_id' => auth()->id(),
                'route' => $request->path(),
                'ip' => $request->ip()
            ], 'high');

            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
