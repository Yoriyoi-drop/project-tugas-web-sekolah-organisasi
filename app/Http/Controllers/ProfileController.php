<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\PasswordVerificationCode;
use App\Mail\PasswordVerificationCode as PasswordVerificationCodeMail;
use App\Services\SecurityService;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $user = Auth::user();
        
        // Log profile view (with error handling)
        try {
            if (class_exists('App\Services\SecurityService')) {
                SecurityService::logActivity('profile_viewed');
                SecurityService::detectSuspiciousActivity(request());
            }
        } catch (\Exception $e) {
            \Log::warning('SecurityService error in profile show: ' . $e->getMessage());
        }
        
        $recentLogs = [];
        try {
            $recentLogs = $user->securityLogs()->latest()->take(5)->get();
        } catch (\Exception $e) {
            \Log::warning('Failed to load security logs: ' . $e->getMessage());
        }
        
        return view('profile.show', [
            'user' => $user,
            'recentLogs' => $recentLogs
        ]);
    }

    public function edit()
    {
        $user = Auth::user();
        
        // Security validation (with error handling)
        try {
            if (class_exists('App\Services\SecurityService')) {
                if (!SecurityService::validateSecureSession()) {
                    SecurityService::logActivity('profile_edit_session_expired', [], 'medium');
                    return redirect()->route('profile.show')
                        ->with('warning', 'Sesi keamanan telah berakhir. Silakan coba lagi.');
                }
                SecurityService::logActivity('profile_edit_accessed');
            }
        } catch (\Exception $e) {
            \Log::warning('SecurityService error in profile edit: ' . $e->getMessage());
        }
        
        return view('profile.edit', [
            'user' => $user
        ]);
    }

    public function requestPasswordChange(Request $request)
    {
        try {
            $key = 'password-change-request:' . $request->ip();
            
            if (RateLimiter::tooManyAttempts($key, 3)) {
                $seconds = RateLimiter::availableIn($key);
                if (class_exists('App\Services\SecurityService')) {
                    SecurityService::logActivity('password_change_rate_limited', ['ip' => $request->ip()], 'high');
                }
                return back()->withErrors(['rate_limit' => "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik."]);
            }

            $request->validate([
                'current_password' => 'required',
                'password' => 'required|min:8|confirmed|different:current_password',
            ]);

            $user = Auth::user();
            
            if (!Hash::check($request->current_password, $user->password)) {
                RateLimiter::hit($key, 300);
                if (class_exists('App\Services\SecurityService')) {
                    SecurityService::logActivity('password_change_failed_auth', [], 'high');
                }
                return back()->withErrors(['current_password' => 'Password saat ini tidak benar.']);
            }

            // Check password strength
            if (!$this->isStrongPassword($request->password)) {
                return back()->withErrors(['password' => 'Password harus mengandung huruf besar, kecil, angka, dan simbol.']);
            }

            // Generate verification code
            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // Delete old codes
            PasswordVerificationCode::where('user_id', $user->id)->where('used', false)->delete();
            
            // Create new code
            PasswordVerificationCode::create([
                'user_id' => $user->id,
                'code' => $code,
                'expires_at' => Carbon::now()->addMinutes(10),
                'ip_address' => $request->ip(),
            ]);

            // Send email (with error handling)
            try {
                Mail::to($user->email)->send(new PasswordVerificationCodeMail($code, $user->name));
            } catch (\Exception $e) {
                \Log::error('Failed to send password verification email: ' . $e->getMessage());
                return back()->withErrors(['email' => 'Gagal mengirim email verifikasi. Silakan coba lagi.']);
            }
            
            session([
                'password_change_data' => [
                    'password' => Hash::make($request->password),
                    'timestamp' => time()
                ]
            ]);

            if (class_exists('App\Services\SecurityService')) {
                SecurityService::logActivity('password_change_requested', [], 'medium');
            }
            
            return redirect()->route('profile.verify-password')->with('success', 'Kode verifikasi telah dikirim ke email Anda.');
            
        } catch (\Exception $e) {
            \Log::error('Password change request error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }

    public function showVerifyPassword()
    {
        if (!session('password_change_data')) {
            return redirect()->route('profile.edit')->withErrors(['error' => 'Sesi tidak valid.']);
        }
        
        return view('profile.verify-password');
    }

    public function verifyPasswordChange(Request $request)
    {
        try {
            $key = 'password-verify:' . $request->ip();
            
            if (RateLimiter::tooManyAttempts($key, 5)) {
                $seconds = RateLimiter::availableIn($key);
                if (class_exists('App\Services\SecurityService')) {
                    SecurityService::logActivity('password_verify_rate_limited', ['ip' => $request->ip()], 'high');
                }
                return back()->withErrors(['rate_limit' => "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik."]);
            }

            $request->validate([
                'verification_code' => 'required|string|size:6',
            ]);

            $user = Auth::user();
            $passwordData = session('password_change_data');
            
            if (!$passwordData || (time() - $passwordData['timestamp']) > 600) {
                if (class_exists('App\Services\SecurityService')) {
                    SecurityService::logActivity('password_change_session_expired', [], 'medium');
                }
                return redirect()->route('profile.edit')->withErrors(['error' => 'Sesi telah kedaluwarsa.']);
            }

            $verificationCode = PasswordVerificationCode::where('user_id', $user->id)
                ->where('code', $request->input('verification_code'))
                ->where('used', false)
                ->first();

            if (!$verificationCode || $verificationCode->isExpired()) {
                RateLimiter::hit($key, 300);
                if (class_exists('App\Services\SecurityService')) {
                    SecurityService::logActivity('password_verify_failed', ['code' => $request->verification_code], 'high');
                }
                return back()->withErrors(['verification_code' => 'Kode verifikasi tidak valid atau telah kedaluwarsa.']);
            }

            // Update password
            $user->update(['password' => $passwordData['password']]);
            
            // Mark code as used
            $verificationCode->update(['used' => true]);
            
            // Clear session
            session()->forget('password_change_data');
            
            if (class_exists('App\Services\SecurityService')) {
                SecurityService::logActivity('password_changed_successfully', [], 'medium');
            }
            
            return redirect()->route('profile.show')->with('success', 'Password berhasil diubah.');
            
        } catch (\Exception $e) {
            \Log::error('Password verification error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }

    public function update(Request $request)
    {
        $key = 'profile-update:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 10)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors(['rate_limit' => "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik."]);
        }

        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female',
            'address' => 'nullable|string|max:500',
            'department' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
        ]);

        $socialLinks = [];
        if ($request->filled('facebook')) $socialLinks['facebook'] = $request->facebook;
        if ($request->filled('twitter')) $socialLinks['twitter'] = $request->twitter;
        if ($request->filled('instagram')) $socialLinks['instagram'] = $request->instagram;
        if ($request->filled('linkedin')) $socialLinks['linkedin'] = $request->linkedin;

        $skills = $request->filled('skills') ? array_filter(explode(',', $request->skills)) : [];

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'bio' => $request->bio,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'address' => $request->address,
            'department' => $request->department,
            'position' => $request->position,
            'social_links' => $socialLinks,
            'skills' => $skills,
        ]);

        // Log profile changes
        $changes = [];
        foreach (['name', 'email', 'phone', 'bio', 'department', 'position'] as $field) {
            if ($user->$field !== $request->$field) {
                $changes[$field] = ['old' => $user->$field, 'new' => $request->$field];
            }
        }
        
        if (!empty($changes)) {
            SecurityService::logActivity('profile_updated', $changes, 'low');
        }

        RateLimiter::hit($key, 60);
        return redirect()->route('profile.show')->with('success', 'Profile berhasil diperbarui.');
    }

    private function isStrongPassword(string $password): bool
    {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/', $password);
    }
}