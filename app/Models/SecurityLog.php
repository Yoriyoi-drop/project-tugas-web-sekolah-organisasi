<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecurityLog extends Model
{
    protected static function booted()
    {
        // Bust the response cache whenever a security log is created/updated
        static::saved(function () {
            // Clear the entire response cache since we can't use tags with file driver
            \Spatie\ResponseCache\Facades\ResponseCache::clear();
        });
    }
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
}
