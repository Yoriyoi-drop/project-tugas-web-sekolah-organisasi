<?php

namespace App\Services;

use App\Models\SecurityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SecurityService
{
    public static function logActivity(string $action, array $data = [], string $riskLevel = 'low'): void
    {
        if (!Auth::check()) return;

        try {
            SecurityLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'data' => $data,
                'risk_level' => $riskLevel,
            ]);
        } catch (\Exception $e) {
            // Silently fail if table doesn't exist
            \Log::warning('Security log failed: ' . $e->getMessage());
        }
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
                'old_ip' => $lastLog->ip_address,
                'new_ip' => $currentIp
            ], 'medium');
            return true;
        }
        
        return false;
    }
}