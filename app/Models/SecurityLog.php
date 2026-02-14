<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\ResponseCache\Facades\ResponseCache;

class SecurityLog extends Model
{

    protected $fillable = [
        'user_id',
        'action',
        'ip_address',
        'user_agent',
        'data',
        'risk_level',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Boot the model with cache invalidation.
     */
    protected static function booted(): void
    {
        // Invalidate cache when security log is created
        static::created(function ($log) {
            // Only invalidate cache for high-risk events to reduce frequency
            if (in_array($log->risk_level, ['high', 'critical'])) {
                self::invalidateSecurityRelatedCaches();
            }
        });
    }

    /**
     * Invalidate caches related to security logs.
     */
    private static function invalidateSecurityRelatedCaches(): void
    {
        // Clear response cache for security audit pages
        if (class_exists(ResponseCache::class)) {
            ResponseCache::clear();
        }

        // Clear any other security-related caches
        \Cache::forget('security_audit_stats');
        \Cache::forget('recent_high_risk_events');
    }
}
