<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailOtp extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code_hash',
        'attempts',
        'sent_count',
        'last_sent_at',
        'expires_at',
        'consumed_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'last_sent_at' => 'datetime',
        'expires_at' => 'datetime',
        'consumed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return now()->greaterThan($this->expires_at);
    }

    public function isConsumed(): bool
    {
        return !is_null($this->consumed_at);
    }

    /**
     * Check if OTP can be resent.
     */
    public function canResend(): bool
    {
        if ($this->isConsumed()) {
            return false;
        }

        if ($this->last_sent_at === null) {
            return true;
        }

        return $this->last_sent_at->diffInMinutes(now()) >= 1;
    }

    /**
     * Verify the OTP code.
     */
    public function verifyCode(string $code): bool
    {
        if ($this->isExpired() || $this->isConsumed()) {
            return false;
        }

        return \Illuminate\Support\Facades\Hash::check($code, $this->code_hash);
    }
}
