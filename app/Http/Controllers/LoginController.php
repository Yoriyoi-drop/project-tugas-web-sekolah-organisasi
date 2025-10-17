<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $user = User::where('email', $request->email)->first();
        
        if ($user && $user->isLocked()) {
            return back()->withErrors([
                'email' => 'Akun terkunci. Coba lagi dalam ' . $user->locked_until->diffInMinutes() . ' menit.',
            ]);
        }

        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            if ($user) {
                $user->unlockAccount();
                $user->update(['last_login_at' => now()]);
            }
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        if ($user) {
            $user->increment('failed_login_attempts');
            if ($user->failed_login_attempts >= 5) {
                $user->lockAccount();
                return back()->withErrors([
                    'email' => 'Terlalu banyak percobaan login. Akun dikunci selama 30 menit.',
                ]);
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->except('password'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}