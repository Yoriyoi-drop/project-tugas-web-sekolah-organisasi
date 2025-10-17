<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FA\Google2FA;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TwoFactorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $user = Auth::user();
        $google2fa = new Google2FA();
        
        if (!$user->two_factor_secret) {
            $user->two_factor_secret = $google2fa->generateSecretKey();
            $user->save();
        }

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $user->two_factor_secret
        );

        // Generate QR code as SVG (no backend required)
        $qrCode = QrCode::size(200)->generate($qrCodeUrl);
        $manualEntryKey = $user->two_factor_secret;

        return view('profile.2fa', compact('qrCode', 'qrCodeUrl', 'manualEntryKey'));
    }

    public function showVerify()
    {
        return view('profile.2fa-verify');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6'
        ]);

        $user = Auth::user();
        $google2fa = new Google2FA();

        if ($google2fa->verifyKey($user->two_factor_secret, $request->code)) {
            session(['2fa_verified' => true]);
            return redirect()->intended(route('profile.show'));
        }

        return back()->withErrors(['code' => 'Kode 2FA tidak valid.']);
    }

    public function enable(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6'
        ]);

        $user = Auth::user();
        $google2fa = new Google2FA();

        if ($google2fa->verifyKey($user->two_factor_secret, $request->code)) {
            $user->update([
                'two_factor_enabled' => true,
                'recovery_codes' => $this->generateRecoveryCodes()
            ]);

            return redirect()->route('profile.show')->with('success', '2FA berhasil diaktifkan.');
        }

        return back()->withErrors(['code' => 'Kode tidak valid.']);
    }

    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required'
        ]);

        if (!Hash::check($request->password, Auth::user()->password)) {
            return back()->withErrors(['password' => 'Password salah.']);
        }

        Auth::user()->update([
            'two_factor_enabled' => false,
            'two_factor_secret' => null,
            'recovery_codes' => null
        ]);

        return redirect()->route('profile.show')->with('success', '2FA berhasil dinonaktifkan.');
    }

    private function generateRecoveryCodes(): array
    {
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = strtoupper(substr(md5(random_bytes(16)), 0, 8));
        }
        return $codes;
    }
}