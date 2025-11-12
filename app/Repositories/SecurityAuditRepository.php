<?php

namespace App\Repositories;

use App\Models\SecurityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class SecurityAuditRepository
{
    /**
     * Tag name for response cache entries.
     */
    const CACHE_TAG = 'security-audit';

    public function __construct()
    {
        // Set the cache tag in the Spatie ResponseCache config
        config(['responsecache.cache_tag' => self::CACHE_TAG]);
    }

    /**
     * Get paginated security logs with filters
     */
    public function getPaginatedLogs(array $filters = []): LengthAwarePaginator
    {
        $query = SecurityLog::with('user')
            ->when(isset($filters['user_id']), function (Builder $query) use ($filters) {
                $query->where('user_id', $filters['user_id']);
            })
            ->when(isset($filters['ip_address']), function (Builder $query) use ($filters) {
                $query->where('ip_address', 'like', "%{$filters['ip_address']}%");
            })
            ->when(isset($filters['status']), function (Builder $query) use ($filters) {
                // status is stored inside the JSON 'data' column
                $query->whereJsonContains('data', ['status' => $filters['status']]);
            })
            ->when(isset($filters['action']), function (Builder $query) use ($filters) {
                $query->where('action', $filters['action']);
            })
            ->when(isset($filters['risk_level']), function (Builder $query) use ($filters) {
                $query->where('risk_level', $filters['risk_level']);
            })
            ->when(isset($filters['date_from']), function (Builder $query) use ($filters) {
                $query->whereDate('created_at', '>=', $filters['date_from']);
            })
            ->when(isset($filters['date_to']), function (Builder $query) use ($filters) {
                $query->whereDate('created_at', '<=', $filters['date_to']);
            })
            ->orderBy('created_at', 'desc');

        return $query->paginate(15);
    }

    /**
     * Get summary statistics for the security dashboard
     */
    public function getSummaryStats(): array
    {
        $today = Carbon::today();

        return [
            'total_otp_attempts' => SecurityLog::where('action', 'like', 'otp%')->count(),
            // Count failed otp verifications but exclude explicitly high risk events
            'failed_verifications' => SecurityLog::where('action', 'otp_verify')
                ->whereJsonContains('data', ['status' => 'error'])
                ->where('risk_level', '<>', 'high')
                ->count(),
            'locked_accounts' => User::where('locked_until', '>', now())->count(),
            'today_events' => SecurityLog::whereDate('created_at', $today)->count(),
            'high_risk_events' => SecurityLog::where('risk_level', 'high')
                ->whereDate('created_at', '>=', now()->subDays(7))
                ->count(),
        ];
    }

    /**
     * Get a list of unique event types for filtering
     */
    public function getEventTypes(): array
    {
        return SecurityLog::select('action')
            ->distinct()
            ->pluck('action')
            ->toArray();
    }

    /**
     * Get most recent high-risk events
     */
    public function getRecentHighRiskEvents(int $limit = 5): array
    {
        return SecurityLog::with('user')
            ->where('risk_level', 'high')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }
}
