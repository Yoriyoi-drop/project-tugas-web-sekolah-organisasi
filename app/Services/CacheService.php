<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CacheService
{
    // Cache TTL in seconds
    const TTL_SHORT = 300;      // 5 minutes
    const TTL_MEDIUM = 1800;   // 30 minutes
    const TTL_LONG = 3600;     // 1 hour
    const TTL_DAILY = 86400;   // 24 hours

    /**
     * Cache organization statistics
     */
    public static function cacheOrganizationStats($organizationId, $callback)
    {
        $key = "organization_stats_{$organizationId}";
        return Cache::remember($key, self::TTL_MEDIUM, $callback);
    }

    /**
     * Cache member counts by status
     */
    public static function cacheMemberStats($organizationId, $callback)
    {
        $key = "member_stats_{$organizationId}";
        return Cache::remember($key, self::TTL_SHORT, $callback);
    }

    /**
     * Cache recent activity
     */
    public static function cacheRecentActivity($organizationId, $callback)
    {
        $key = "recent_activity_{$organizationId}";
        return Cache::remember($key, self::TTL_SHORT, $callback);
    }

    /**
     * Cache upcoming events
     */
    public static function cacheUpcomingEvents($organizationId, $limit, $callback)
    {
        $key = "upcoming_events_{$organizationId}_{$limit}";
        return Cache::remember($key, self::TTL_MEDIUM, $callback);
    }

    /**
     * Cache active organizations list
     */
    public static function cacheActiveOrganizations($callback)
    {
        return Cache::remember('active_organizations', self::TTL_LONG, $callback);
    }

    /**
     * Cache user permissions
     */
    public static function cacheUserPermissions($userId, $callback)
    {
        $key = "user_permissions_{$userId}";
        return Cache::remember($key, self::TTL_MEDIUM, $callback);
    }

    /**
     * Cache dashboard statistics
     */
    public static function cacheDashboardStats($callback)
    {
        return Cache::remember('dashboard_stats', self::TTL_SHORT, $callback);
    }

    /**
     * Clear organization-related cache
     */
    public static function clearOrganizationCache($organizationId)
    {
        $patterns = [
            "organization_stats_{$organizationId}",
            "member_stats_{$organizationId}",
            "recent_activity_{$organizationId}",
            "upcoming_events_{$organizationId}_*"
        ];

        foreach ($patterns as $pattern) {
            if (str_contains($pattern, '*')) {
                // For wildcard patterns, we need to use cache tags or flush all
                Cache::flush();
            } else {
                Cache::forget($pattern);
            }
        }
    }

    /**
     * Clear user permissions cache
     */
    public static function clearUserPermissionsCache($userId)
    {
        Cache::forget("user_permissions_{$userId}");
    }

    /**
     * Clear dashboard cache
     */
    public static function clearDashboardCache()
    {
        Cache::forget('dashboard_stats');
        Cache::forget('active_organizations');
    }

    /**
     * Cache query results with automatic invalidation
     */
    public static function cacheQuery($key, $query, $ttl = self::TTL_MEDIUM)
    {
        return Cache::remember($key, $ttl, function () use ($query) {
            return $query->get();
        });
    }

    /**
     * Cache count results for better performance
     */
    public static function cacheCount($key, $query, $ttl = self::TTL_SHORT)
    {
        return Cache::remember($key, $ttl, function () use ($query) {
            return $query->count();
        });
    }
}
