<?php

namespace App\Observers;

use App\Models\Organization;
use App\Services\CacheService;

class OrganizationObserver
{
    /**
     * Handle the Organization "created" event.
     */
    public function created(Organization $organization): void
    {
        CacheService::clearDashboardCache();
    }

    /**
     * Handle the Organization "updated" event.
     */
    public function updated(Organization $organization): void
    {
        CacheService::clearOrganizationCache($organization->id);
        CacheService::clearDashboardCache();
    }

    /**
     * Handle the Organization "deleted" event.
     */
    public function deleted(Organization $organization): void
    {
        CacheService::clearOrganizationCache($organization->id);
        CacheService::clearDashboardCache();
    }

    /**
     * Handle the Organization "restored" event.
     */
    public function restored(Organization $organization): void
    {
        CacheService::clearOrganizationCache($organization->id);
        CacheService::clearDashboardCache();
    }

    /**
     * Handle the Organization "force deleted" event.
     */
    public function forceDeleted(Organization $organization): void
    {
        CacheService::clearOrganizationCache($organization->id);
        CacheService::clearDashboardCache();
    }
}
