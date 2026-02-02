<?php

namespace Tests\Unit;

use App\Services\CacheService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

class CacheServiceTest extends TestCase
{
    public function test_cache_organization_stats()
    {
        $organizationId = 1;
        $expectedData = ['members_count' => 10, 'events_count' => 5];
        
        $result = CacheService::cacheOrganizationStats($organizationId, function() use ($expectedData) {
            return $expectedData;
        });

        $this->assertEquals($expectedData, $result);
        
        // Check if the data was cached
        $cachedData = Cache::get("organization_stats_{$organizationId}");
        $this->assertEquals($expectedData, $cachedData);
    }

    public function test_cache_member_stats()
    {
        $organizationId = 1;
        $expectedData = ['active' => 8, 'inactive' => 2];
        
        $result = CacheService::cacheMemberStats($organizationId, function() use ($expectedData) {
            return $expectedData;
        });

        $this->assertEquals($expectedData, $result);
        
        // Check if the data was cached
        $cachedData = Cache::get("member_stats_{$organizationId}");
        $this->assertEquals($expectedData, $cachedData);
    }

    public function test_cache_recent_activity()
    {
        $organizationId = 1;
        $expectedData = ['activity1', 'activity2'];
        
        $result = CacheService::cacheRecentActivity($organizationId, function() use ($expectedData) {
            return $expectedData;
        });

        $this->assertEquals($expectedData, $result);
        
        // Check if the data was cached
        $cachedData = Cache::get("recent_activity_{$organizationId}");
        $this->assertEquals($expectedData, $cachedData);
    }

    public function test_cache_upcoming_events()
    {
        $organizationId = 1;
        $limit = 5;
        $expectedData = ['event1', 'event2'];
        
        $result = CacheService::cacheUpcomingEvents($organizationId, $limit, function() use ($expectedData) {
            return $expectedData;
        });

        $this->assertEquals($expectedData, $result);
        
        // Check if the data was cached
        $cachedData = Cache::get("upcoming_events_{$organizationId}_{$limit}");
        $this->assertEquals($expectedData, $cachedData);
    }

    public function test_cache_active_organizations()
    {
        $expectedData = ['org1', 'org2'];
        
        $result = CacheService::cacheActiveOrganizations(function() use ($expectedData) {
            return $expectedData;
        });

        $this->assertEquals($expectedData, $result);
        
        // Check if the data was cached
        $cachedData = Cache::get("active_organizations");
        $this->assertEquals($expectedData, $cachedData);
    }

    public function test_cache_user_permissions()
    {
        $userId = 1;
        $expectedData = ['permission1', 'permission2'];
        
        $result = CacheService::cacheUserPermissions($userId, function() use ($expectedData) {
            return $expectedData;
        });

        $this->assertEquals($expectedData, $result);
        
        // Check if the data was cached
        $cachedData = Cache::get("user_permissions_{$userId}");
        $this->assertEquals($expectedData, $cachedData);
    }

    public function test_cache_dashboard_stats()
    {
        $expectedData = ['total_users' => 100, 'total_orgs' => 10];
        
        $result = CacheService::cacheDashboardStats(function() use ($expectedData) {
            return $expectedData;
        });

        $this->assertEquals($expectedData, $result);
        
        // Check if the data was cached
        $cachedData = Cache::get("dashboard_stats");
        $this->assertEquals($expectedData, $cachedData);
    }

    public function test_clear_user_permissions_cache()
    {
        $userId = 1;
        $data = ['permission1', 'permission2'];
        
        // First, cache some data
        Cache::put("user_permissions_{$userId}", $data, 300);
        
        // Verify it's cached
        $this->assertEquals($data, Cache::get("user_permissions_{$userId}"));
        
        // Clear the cache
        CacheService::clearUserPermissionsCache($userId);
        
        // Verify it's cleared
        $this->assertNull(Cache::get("user_permissions_{$userId}"));
    }

    public function test_clear_dashboard_cache()
    {
        $dashboardData = ['total_users' => 100, 'total_orgs' => 10];
        $orgsData = ['org1', 'org2'];
        
        // First, cache some data
        Cache::put("dashboard_stats", $dashboardData, 300);
        Cache::put("active_organizations", $orgsData, 300);
        
        // Verify it's cached
        $this->assertEquals($dashboardData, Cache::get("dashboard_stats"));
        $this->assertEquals($orgsData, Cache::get("active_organizations"));
        
        // Clear the cache
        CacheService::clearDashboardCache();
        
        // Verify it's cleared
        $this->assertNull(Cache::get("dashboard_stats"));
        $this->assertNull(Cache::get("active_organizations"));
    }
}