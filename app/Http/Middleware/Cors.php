<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\HandleCors as BaseCors;

class Cors extends BaseCors
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, \Closure $next)
    {
        // Add custom CORS logic if needed
        return parent::handle($request, $next);
    }
}
