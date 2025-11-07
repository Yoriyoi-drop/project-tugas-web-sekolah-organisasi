<?php

namespace App\Http\CacheProfiles;

use Illuminate\Http\Request;
use Spatie\ResponseCache\CacheProfiles\CacheAllSuccessfulGetRequests;
use Symfony\Component\HttpFoundation\Response;

class SecurityAuditCacheProfile extends CacheAllSuccessfulGetRequests
{
    /**
     * Return a shorter cache time (5 minutes) for security audit pages.
     */
    public function cacheRequestUntil(Request $request): \DateTime
    {
        return now()->addMinutes(5);
    }

    /**
     * Only cache successful responses from admin/security/* routes.
     */
    public function shouldCacheRequest(Request $request): bool
    {
        $isSecurityAuditRoute = str_starts_with($request->path(), 'admin/security/');
        return $request->isMethod('GET') && $isSecurityAuditRoute;
    }

    /**
     * Only cache 200 OK responses.
     */
    public function shouldCacheResponse(Response $response): bool
    {
        return $response->isSuccessful();
    }
}
