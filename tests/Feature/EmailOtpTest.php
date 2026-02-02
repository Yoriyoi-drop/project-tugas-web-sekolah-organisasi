<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class EmailOtpTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_receives_otp_after_registration(): void
    {
        Notification::fake();

        // Visit the registration page to set CSRF token in session
        $this->get(route('register'));

        $response = $this->post(route('register.store'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'nik' => '1234567890123456',
            'nis' => '1234567890',
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('otp.show'));
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_can_verify_with_correct_otp(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'vrfy@example.com',
            'email_verified_at' => null,
        ]);

        // Set the user ID in session for OTP verification
        $this->withSession(['otp_user_id' => $user->id]);

        // Visit the OTP page to set CSRF token in session
        $this->get(route('otp.show'));

        $code = $user->generateEmailOtp('127.0.0.1', 'PHPUnit');

        $response = $this->post(route('otp.verify'), [
            'code' => $code,
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('login'));
        $this->assertNotNull($user->fresh()->email_verified_at);
    }
}
