<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OptimizeResponse
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        if ($response->headers->get('Content-Type') === 'text/html; charset=UTF-8') {
            $content = $response->getContent();
            $content = preg_replace('/\s+/', ' ', $content);
            $content = preg_replace('/>\s+</', '><', $content);
            $response->setContent($content);
        }
        
        return $response;
    }
}