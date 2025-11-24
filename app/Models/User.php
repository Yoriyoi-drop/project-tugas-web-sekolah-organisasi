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

use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles {
        hasRole as hasSpatieRole;
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        // We use OTP for verification, handled manually in controllers.
        // This override prevents Laravel from trying to send the default link
        // which requires the 'verification.verify' route.
    }

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

    // Penanganan role & izin disediakan oleh Spatie\Permission melalui trait HasRoles.
    // Paket ini menyediakan metode seperti assignRole, hasRole, givePermissionTo, can, dll.

    /**
     * Periksa role pengguna menggunakan izin Spatie terlebih dahulu, dengan fallback legacy.
     */
    public function hasRole(string $role): bool
    {
        try {
            // Pertama periksa menggunakan metode asli Spatie
            if ($this->hasSpatieRole($role)) {
                return true;
            }
            
            // Periksa menggunakan nama atau slug role Spatie
            $spatieRole = \Spatie\Permission\Models\Role::where('name', $role)->orWhere('slug', $role)->first();
            if ($spatieRole && $this->roleHasDirectPermission($spatieRole->id)) {
                return true;
            }
            
            // Fallback legacy: periksa legacy role_user -> roles.slug
            $legacy = \Illuminate\Support\Facades\DB::table('role_user')
                ->join('roles', 'role_user.role_id', '=', 'roles.id')
                ->where('role_user.user_id', $this->id)
                ->where('roles.slug', $role)
                ->exists();
            
            return $legacy;
        } catch (\Exception $e) {
            \Log::error('Error in hasRole check: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Periksa apakah pengguna memiliki ID role tertentu menggunakan tabel pivot Spatie.
     */
    private function roleHasDirectPermission(int $roleId): bool
    {
        try {
            return \Illuminate\Support\Facades\DB::table('model_has_roles')
                ->where('model_type', self::class)
                ->where('model_id', $this->id)
                ->where('role_id', $roleId)
                ->exists();
        } catch (\Exception $e) {
            \Log::error('Error in roleHasDirectPermission: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Periksa kemampuan pengguna menggunakan izin Spatie terlebih dahulu, dengan fallback legacy.
     */
    public function hasAbility(string $ability): bool
    {
        try {
            // Admins have all abilities
            if ($this->isAdmin()) {
                return true;
            }

            // Pertama periksa menggunakan metode can asli Spatie
            if ($this->can($ability)) {
                return true;
            }
            
            // Periksa menggunakan tabel izin Spatie
            $permission = \Spatie\Permission\Models\Permission::where('name', $ability)->first();
            if ($permission && $this->permissionHasDirectPermission($permission->id)) {
                return true;
            }
            
            // Fallback legacy: abilities.slug -> ability_role -> role_user
            $abilityRow = \Illuminate\Support\Facades\DB::table('abilities')->where('slug', $ability)->first();
            if ($abilityRow) {
                $roleIdsLegacy = \Illuminate\Support\Facades\DB::table('role_user')->where('user_id', $this->id)->pluck('role_id')->toArray();
                $roleIdsSpatie = \Illuminate\Support\Facades\DB::table('model_has_roles')
                    ->where('model_type', self::class)
                    ->where('model_id', $this->id)
                    ->pluck('role_id')
                    ->toArray();
                $roleIds = array_values(array_unique(array_merge($roleIdsLegacy, $roleIdsSpatie)));
                
                if (!empty($roleIds)) {
                    $has = \Illuminate\Support\Facades\DB::table('ability_role')
                        ->where('ability_role.ability_id', $abilityRow->id)
                        ->whereIn('ability_role.role_id', $roleIds)
                        ->exists();
                    if ($has) return true;
                }
            }

            return false;
        } catch (\Exception $e) {
            \Log::error('Error in hasAbility check: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Periksa apakah pengguna memiliki ID izin tertentu menggunakan tabel pivot Spatie.
     */
    private function permissionHasDirectPermission(int $permissionId): bool
    {
        try {
            return \Illuminate\Support\Facades\DB::table('role_has_permissions')
                ->join('model_has_roles', 'role_has_permissions.role_id', '=', 'model_has_roles.role_id')
                ->where('model_has_roles.model_type', self::class)
                ->where('model_has_roles.model_id', $this->id)
                ->where('role_has_permissions.permission_id', $permissionId)
                ->exists();
        } catch (\Exception $e) {
            \Log::error('Error in permissionHasDirectPermission: ' . $e->getMessage());
            return false;
        }
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
    
    /**
     * Check if user has any of the given roles.
     */
    public function hasAnyRole(string ...$roles): bool
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Sinkronkan role antara sistem legacy dan Spatie.
     */
    public function syncLegacyRolesToSpatie(): void
    {
        // Dapatkan role legacy
        $legacyRoleIds = \Illuminate\Support\Facades\DB::table('role_user')
            ->where('user_id', $this->id)
            ->pluck('role_id')
            ->toArray();
        
        if (empty($legacyRoleIds)) {
            return;
        }
        
        // Dapatkan nama role dari ID legacy
        $legacyRoleNames = \Illuminate\Support\Facades\DB::table('roles')
            ->whereIn('id', $legacyRoleIds)
            ->pluck('name')
            ->toArray();
        
        // Tetapkan role melalui Spatie
        try {
            $this->syncRoles($legacyRoleNames);
        } catch (\Exception $e) {
            \Log::error('Failed to sync legacy roles to Spatie: ' . $e->getMessage());
        }
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

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            // Periksa apakah file ada di penyimpanan
            if (\Storage::disk('public')->exists($this->avatar)) {
                return \Storage::url($this->avatar);
            } else {
                // File tidak ada di penyimpanan, mungkin ada ketidakkonsistenan database
                // Perbarui database untuk menghapus referensi
                $this->update(['avatar' => null]);
            }
        }
        
        // Fallback ke avatar default
        return asset('images/default-avatar.svg');
    }

}
