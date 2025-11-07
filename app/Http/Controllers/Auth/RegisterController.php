<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    public function show()
    {
        $hasNik = Schema::hasColumn('users', 'nik');
        $hasNis = Schema::hasColumn('users', 'nis');
        return view('auth.register', compact('hasNik', 'hasNis'));
    }

    public function store(RegisterRequest $request)
    {
        $hasNik = Schema::hasColumn('users', 'nik');
        $hasNis = Schema::hasColumn('users', 'nis');

        if (!$hasNik || !$hasNis) {
            return back()->withErrors(['general' => 'Fitur pendaftaran belum lengkap. Silakan hubungi admin untuk menambahkan kolom NIK dan NIS pada panel.'])->withInput();
        }

        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'nik' => $data['nik'],
            'nis' => $data['nis'],
        ]);

        // Generate OTP and save in session
        $otp = $user->generateEmailOtp();
        session(['otp_user_id' => $user->id]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('otp.show');
        event(new Registered($user));

        // Redirect to login (verification disabled)
        return redirect()->route('login')->with('status', 'Pendaftaran berhasil. Silakan login.');
    }
}
