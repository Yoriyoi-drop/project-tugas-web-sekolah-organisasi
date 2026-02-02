<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ApiVersionMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $version): Response
    {
        // Add version to response headers
        $response = $next($request);
        
        if ($response instanceof JsonResponse) {
            $response->headers->set('API-Version', $version);
            $response->headers->set('API-Supported-Versions', 'v1,v2');
        }

        // Log API version usage
        \Log::info('API Request', [
            'version' => $version,
            'endpoint' => $request->path(),
            'method' => $request->method(),
            'user_id' => auth()->id(),
            'ip' => $request->ip(),
        ]);

        return $response;
    }
}
