<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Services\SecurityService;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'phone',
        'bio',
        'avatar',
        'birth_date',
        'gender',
        'address',
        'role',
        'department',
        'position',
        'social_links',
        'skills',
        'is_active',
        'last_login_at',
        'two_factor_enabled',
        'two_factor_secret',
        'recovery_codes',
        'failed_login_attempts',
        'locked_until',
        'nik',
        'nis',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'birth_date' => 'date',
            'social_links' => 'array',
            'skills' => 'array',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
            'two_factor_enabled' => 'boolean',
            'recovery_codes' => 'array',
            'locked_until' => 'datetime',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    // Security methods
    public function getSecurePhoneAttribute(): string
    {
        return $this->phone ? SecurityService::maskSensitiveData($this->phone, 2) : '';
    }

    public function getSecureAddressAttribute(): string
    {
        return $this->address ? SecurityService::maskSensitiveData($this->address, 10) : '';
    }

    public function setPhoneAttribute($value): void
    {
        $this->attributes['phone'] = $value ? SecurityService::encryptSensitiveField($value) : null;
    }

    public function getPhoneAttribute($value): ?string
    {
        return $value ? SecurityService::decryptSensitiveField($value) : null;
    }

    public function setAddressAttribute($value): void
    {
        $this->attributes['address'] = $value ? SecurityService::encryptSensitiveField($value) : null;
    }

    public function getAddressAttribute($value): ?string
    {
        return $value ? SecurityService::decryptSensitiveField($value) : null;
    }

    public function securityLogs()
    {
        return $this->hasMany(SecurityLog::class);
    }

    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    public function getTwoFactorEnabledAttribute($value)
    {
        return $value ?? false;
    }

    public function getFailedLoginAttemptsAttribute($value)
    {
        return $value ?? 0;
    }

    public function lockAccount(int $minutes = 30): void
    {
        $this->update([
            'locked_until' => now()->addMinutes($minutes),
            'failed_login_attempts' => $this->failed_login_attempts + 1
        ]);
    }

    public function unlockAccount(): void
    {
        $this->update([
            'locked_until' => null,
            'failed_login_attempts' => 0
        ]);
    }
}
