<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class TwoFactorController extends Controller
{
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

        // Option A: SVG (Current - Recommended)
        $qrCode = QrCode::size(200)->generate($qrCodeUrl);
        
        // Option B: PNG with BaconQrCode (Alternative)
        // $renderer = new ImageRenderer(
        //     new RendererStyle(200),
        //     new SvgImageBackEnd()
        // );
        // $writer = new Writer($renderer);
        // $qrCode = base64_encode($writer->writeString($qrCodeUrl));

        $manualEntryKey = $user->two_factor_secret;

        return view('profile.2fa', compact('qrCode', 'qrCodeUrl', 'manualEntryKey'));
    }
}