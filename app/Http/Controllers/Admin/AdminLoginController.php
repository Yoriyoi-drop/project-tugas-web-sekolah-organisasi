<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AdminLoginController extends Controller
{
    /**
     * Tampilkan form login admin
     */
    public function showLoginForm()
    {
        // Jika pengguna sudah login dan merupakan admin, arahkan ke dashboard admin
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        
        // Jika pengguna sudah login tapi bukan admin, arahkan ke halaman utama
        if (Auth::check() && !Auth::user()->isAdmin()) {
            return redirect()->route('home');
        }
        
        return view('admin.auth.login');
    }

    /**
     * Proses login admin
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        // Cek rate limiting
        $this->ensureIsNotRateLimited($request);

        $user = User::where('email', $request->email)->first();

        // Cek apakah user ada dan merupakan admin
        if (!$user || !$user->is_admin) {
            $this->incrementLoginAttempts($request);
            
            throw ValidationException::withMessages([
                'email' => ['Akun ini bukan akun admin atau tidak ditemukan.'],
            ]);
        }

        // Cek apakah akun terkunci
        if ($user->isLocked()) {
            $lockedMinutes = $user->locked_until instanceof \Carbon\Carbon 
                ? $user->locked_until->diffInMinutes() 
                : 0;
            return back()->withErrors([
                'email' => 'Akun terkunci. Coba lagi dalam ' . $lockedMinutes . ' menit.',
            ]);
        }

        // Cek kredensial login
        $credentials = $request->only('email', 'password');
        $credentials['is_admin'] = true; // Hanya izinkan login jika user adalah admin

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user->unlockAccount();
            $user->update(['last_login_at' => now()]);

            // Regenerasi session untuk mencegah fixation attack
            $request->session()->regenerate();

            // Hapus rate limiter untuk IP ini
            $this->clearLoginAttempts($request);

            return redirect()->intended(route('admin.dashboard'));
        }

        // Jika login gagal
        $this->incrementLoginAttempts($request);

        $user->increment('failed_login_attempts');
        if ($user->failed_login_attempts >= 5) {
            $user->lockAccount();
            return back()->withErrors([
                'email' => 'Terlalu banyak percobaan login. Akun dikunci selama 30 menit.',
            ]);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->except('password'));
    }

    /**
     * Logout admin
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login');
    }

    /**
     * Cek apakah user terkena rate limit
     */
    protected function ensureIsNotRateLimited(Request $request)
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => __('Too many login attempts. Please try again in :seconds seconds.', [
                'seconds' => $seconds,
            ]),
        ]);
    }

    /**
     * Dapatkan kunci throttle untuk rate limiting
     */
    protected function throttleKey(Request $request)
    {
        return strtolower($request->input('email')).'|'.$request->ip();
    }

    /**
     * Tambahkan jumlah percobaan login
     */
    protected function incrementLoginAttempts(Request $request)
    {
        RateLimiter::hit($this->throttleKey($request));
    }

    /**
     * Bersihkan percobaan login
     */
    protected function clearLoginAttempts(Request $request)
    {
        RateLimiter::clear($this->throttleKey($request));
    }
}