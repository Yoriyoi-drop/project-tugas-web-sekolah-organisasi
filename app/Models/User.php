<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Services\SecurityService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    public function generateEmailOtp($ip = null, $userAgent = null)
    {
        // Delete any existing OTP for this user
        EmailOtp::where('user_id', $this->id)->delete();

        // Generate new OTP
        $code = strtoupper(Str::random(6));
        $otp = EmailOtp::create([
            'user_id' => $this->id,
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes(10),
            'sent_count' => 1,
            'last_sent_at' => now(),
            'ip_address' => $ip ?? request()->ip(),
            'user_agent' => $userAgent ?? request()->userAgent()
        ]);

        // Log OTP generation
        SecurityService::logOtpGenerate($this->id);

        return $code;
    }

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
        'nik_hash',
        'nis_hash'
    ];

    // Role & permission handling is provided by Spatie\Permission via HasRoles trait.
    // The package exposes methods such as assignRole, hasRole, givePermissionTo, can, etc.

    /**
     * Backwards-compatibility: check role using legacy `role_user` pivot or Spatie model_has_roles.
     */
    public function hasRole(string $role): bool
    {
        // Check legacy role_user -> roles.slug
        $legacy = \Illuminate\Support\Facades\DB::table('role_user')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->where('role_user.user_id', $this->id)
            ->where('roles.slug', $role)
            ->exists();

        if ($legacy) return true;

        // Check Spatie model_has_roles -> roles.name (or slug if present)
        $roleRow = \Illuminate\Support\Facades\DB::table('roles')->where('slug', $role)->orWhere('name', $role)->first();
        if ($roleRow) {
            return \Illuminate\Support\Facades\DB::table('model_has_roles')
                ->where('model_type', self::class)
                ->where('model_id', $this->id)
                ->where('role_id', $roleRow->id)
                ->exists();
        }

        return false;
    }

    /**
     * Backwards-compatibility: check ability via legacy ability_role -> role_user or Spatie role/permission pivots.
     */
    public function hasAbility(string $ability): bool
    {
        // Legacy path: abilities.slug -> ability_role -> role_user
        $abilityRow = \Illuminate\Support\Facades\DB::table('abilities')->where('slug', $ability)->first();
        if ($abilityRow) {
            $roleIdsLegacy = \Illuminate\Support\Facades\DB::table('role_user')->where('user_id', $this->id)->pluck('role_id')->toArray();
            $roleIdsSpatie = \Illuminate\Support\Facades\DB::table('model_has_roles')
                ->where('model_type', self::class)
                ->where('model_id', $this->id)
                ->pluck('role_id')
                ->toArray();
            $roleIds = array_values(array_unique(array_merge($roleIdsLegacy, $roleIdsSpatie)));
            // debug info (tests):
            \Illuminate\Support\Facades\Log::debug('hasAbility check', ['user_id' => $this->id, 'ability' => $ability, 'ability_id' => $abilityRow->id, 'role_ids' => $roleIds]);
            if (!empty($roleIds)) {
                $has = \Illuminate\Support\Facades\DB::table('ability_role')
                    ->where('ability_role.ability_id', $abilityRow->id)
                    ->whereIn('ability_role.role_id', $roleIds)
                    ->exists();
                \Illuminate\Support\Facades\Log::debug('ability_role match', ['has' => $has]);
                if ($has) return true;
            }
        }

        // Spatie path: permissions.name -> role_has_permissions -> model_has_roles
        $perm = \Illuminate\Support\Facades\DB::table('permissions')->where('name', $ability)->first();
        if ($perm) {
            return \Illuminate\Support\Facades\DB::table('role_has_permissions')
                ->join('model_has_roles', 'role_has_permissions.role_id', '=', 'model_has_roles.role_id')
                ->where('model_has_roles.model_type', self::class)
                ->where('model_has_roles.model_id', $this->id)
                ->where('role_has_permissions.permission_id', $perm->id)
                ->exists();
        }

        return false;
    }

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

    // NIK encrypted
    public function setNikAttribute($value): void
    {
        $this->attributes['nik'] = $value ? SecurityService::encryptSensitiveField($value) : null;
        $this->attributes['nik_hash'] = $value ? hash('sha256', self::normalizeId($value)) : null;
    }

    public function getNikAttribute($value): ?string
    {
        return $value ? SecurityService::decryptSensitiveField($value) : null;
    }

    // NIS encrypted
    public function setNisAttribute($value): void
    {
        $this->attributes['nis'] = $value ? SecurityService::encryptSensitiveField($value) : null;
        $this->attributes['nis_hash'] = $value ? hash('sha256', self::normalizeId($value)) : null;
    }

    public function getNisAttribute($value): ?string
    {
        return $value ? SecurityService::decryptSensitiveField($value) : null;
    }

    private static function normalizeId(?string $value): string
    {
        if (!$value) return '';
        $value = trim($value);
        return preg_replace('/[^A-Za-z0-9]/', '', $value);
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
