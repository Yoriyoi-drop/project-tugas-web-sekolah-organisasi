<?php

namespace App\Services;

use App\Models\SecurityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SecurityService
{
    // Security action constants
    public const ACTION_LOGIN = 'login';
    public const ACTION_LOGOUT = 'logout';
    public const ACTION_REGISTER = 'register';
    public const ACTION_PASSWORD_RESET = 'password_reset';
    public const ACTION_EMAIL_VERIFY = 'email_verify';
    public const ACTION_PROFILE_UPDATE = 'profile_update';
    public const ACTION_FAILED_LOGIN = 'failed_login';
    public const ACTION_OTP_GENERATE = 'otp_generate';
    public const ACTION_OTP_VERIFY = 'otp_verify';
    public const ACTION_OTP_FAILED = 'otp_failed';
    public const ACTION_ACCOUNT_LOCKED = 'account_locked';
    public const ACTION_ACCOUNT_UNLOCKED = 'account_unlocked';

    // Risk level constants
    public const RISK_LOW = 'low';
    public const RISK_MEDIUM = 'medium';
    public const RISK_HIGH = 'high';

    public static function logActivity(string $action, array $data = [], string $riskLevel = self::RISK_LOW, ?int $userId = null): void
    {
        try {
            SecurityLog::create([
                'user_id' => $userId ?? Auth::id(),
                'action' => $action,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'data' => $data,
                'risk_level' => $riskLevel,
            ]);
        } catch (\Exception $e) {
            // Log error but don't disrupt user flow
            \Log::warning('Security log failed: ' . $e->getMessage());
        }
    }

    public static function logSecurityEvent(?int $userId, string $action, string $description = '', string $riskLevel = self::RISK_LOW): void
    {
        self::logActivity($action, ['description' => $description], $riskLevel, $userId);
    }

    public static function logFailedLogin(string $email): void
    {
        self::logActivity(
            self::ACTION_FAILED_LOGIN,
            ['email' => $email],
            self::RISK_MEDIUM
        );
    }

    public static function logLogin(int $userId): void
    {
        self::logActivity(
            self::ACTION_LOGIN,
            [],
            self::RISK_LOW,
            $userId
        );
    }

    public static function logOtpGenerate(int $userId): void
    {
        self::logActivity(
            self::ACTION_OTP_GENERATE,
            [],
            self::RISK_LOW,
            $userId
        );
    }

    public static function logOtpVerify(int $userId, bool $success): void
    {
        self::logActivity(
            $success ? self::ACTION_OTP_VERIFY : self::ACTION_OTP_FAILED,
            ['success' => $success],
            $success ? self::RISK_LOW : self::RISK_MEDIUM,
            $userId
        );
    }

    public static function logProfileUpdate(int $userId, array $changedFields): void
    {
        self::logActivity(
            self::ACTION_PROFILE_UPDATE,
            ['changed_fields' => $changedFields],
            self::RISK_LOW,
            $userId
        );
    }

    public static function maskSensitiveData(string $data, int $visibleChars = 3): string
    {
        $length = strlen($data);
        if ($length <= $visibleChars * 2) {
            return str_repeat('*', $length);
        }

        return substr($data, 0, $visibleChars) . str_repeat('*', $length - $visibleChars * 2) . substr($data, -$visibleChars);
    }

    public static function encryptSensitiveField(string $value): string
    {
        return encrypt($value);
    }

    public static function decryptSensitiveField(string $value): string
    {
        try {
            return decrypt($value);
        } catch (\Exception $e) {
            return $value;
        }
    }

    public static function validateSecureSession(): bool
    {
        $lastActivity = session('last_security_check', 0);
        $currentTime = time();

        if ($currentTime - $lastActivity > 1800) { // 30 minutes
            session(['last_security_check' => $currentTime]);
            return false;
        }

        session(['last_security_check' => $currentTime]);
        return true;
    }

    public static function detectSuspiciousActivity(Request $request): bool
    {
        $user = Auth::user();
        $currentIp = $request->ip();

        // Check for IP changes
        $lastLog = SecurityLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastLog && $lastLog->ip_address !== $currentIp) {
            self::logActivity('ip_change_detected', [
                'message' => 'IP address changed during session',
                'timestamp' => now()->toISOString()
            ], 'medium', $user->id);
            return true;
        }

        return false;
    }
}
