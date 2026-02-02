<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\LoggingService;
use Symfony\Component\HttpFoundation\Response;

class ApiLoggingMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        
        // Log incoming request
        $requestData = [
            'headers' => $request->headers->all(),
            'query' => $request->query->all(),
        ];
        
        // Don't log sensitive data
        if ($request->hasFile('file') || $request->has('password')) {
            $requestData['body'] = '[FILTERED]';
        } else {
            $requestData['body'] = $request->all();
        }

        $response = $next($request);

        $duration = round((microtime(true) - $startTime) * 1000, 2);

        // Log the API request and response
        LoggingService::logApiRequest(
            $request->method(),
            $request->fullUrl(),
            $requestData,
            $response,
            auth()->id(),
            $duration
        );

        return $response;
    }
}
