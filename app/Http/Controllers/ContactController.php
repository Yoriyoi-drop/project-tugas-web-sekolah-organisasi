<?php
namespace App\Http\Controllers;


use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class ContactController extends Controller
{
    public function index()
    {
        return view('pages.kontak');
    }

    public function send(Request $request)
    {
        // Rate limiting
        $key = 'contact-form:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik."
            ]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|min:10|max:1000',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.max' => 'Nama lengkap maksimal 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'subject.max' => 'Subjek maksimal 255 karakter.',
            'message.required' => 'Pesan wajib diisi.',
            'message.min' => 'Pesan minimal 10 karakter.',
            'message.max' => 'Pesan maksimal 1000 karakter.',
        ]);

        RateLimiter::hit($key, 300); // 5 minutes

        // Here you can send email, save to DB, etc.
        // For now, just return with success message.
        return back()->with('success', 'Terima kasih! Pesan Anda berhasil dikirim. Kami akan merespons dalam 1-2 hari kerja.')->withInput([]);
    }
}
