<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\EmailOtpNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class EmailOtpController extends Controller
{
    public function show(Request $request)
    {
        $userId = $request->session()->get('otp_user_id');
        if (!$userId) {
            return redirect()->route('register')->withErrors(['general' => 'Sesi OTP tidak ditemukan. Silakan daftar atau login.']);
        }
        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('register')->withErrors(['general' => 'Pengguna tidak ditemukan.']);
        }
        return view('auth.otp', compact('user'));
    }

    public function send(Request $request)
    {
        $userId = $request->session()->get('otp_user_id');
        if (!$userId) {
            return redirect()->route('register')->withErrors(['general' => 'Sesi OTP tidak ditemukan.']);
        }
        $user = User::findOrFail($userId);

        $key = 'otp-send:' . $user->id . ':' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors(['otp' => 'Terlalu banyak permintaan. Coba lagi dalam ' . $seconds . ' detik.']);
        }

        $emailOtp = \App\Models\EmailOtp::where('user_id', $user->id)->latest()->first();
        if ($emailOtp && !$emailOtp->canResend()) {
            return back()->withErrors(['otp' => 'Tunggu beberapa saat sebelum mengirim ulang OTP.']);
        }

        $code = $user->generateEmailOtp($request->ip(), $request->userAgent());
        $user->notify(new EmailOtpNotification($code));
        RateLimiter::hit($key, 3600);

        return back()->with('status', 'Kode OTP telah dikirim ke email Anda.');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $userId = $request->session()->get('otp_user_id');
        if (!$userId) {
            return redirect()->route('register')->withErrors(['general' => 'Sesi OTP tidak ditemukan.']);
        }
        $user = User::findOrFail($userId);

        $key = 'otp-verify:' . $user->id . ':' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 10)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors(['otp' => 'Terlalu banyak percobaan. Coba lagi dalam ' . $seconds . ' detik.']);
        }

        $emailOtp = \App\Models\EmailOtp::where('user_id', $user->id)->latest()->first();
        if ($emailOtp && $emailOtp->verifyCode($request->input('code'))) {
            $request->session()->forget('otp_user_id');
            return redirect()->route('login')->with('status', 'Email berhasil diverifikasi. Silakan login.');
        }

        RateLimiter::hit($key, 600);
        return back()->withErrors(['otp' => 'Kode OTP tidak valid atau sudah kedaluwarsa.']);
    }
}
