<?php

namespace Tests\Feature\Security;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_does_not_autologin_user()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'nik' => '1234567890123456',
            'nis' => '1234567890',
        ];

        // Ensure we have the columns (mocking schema check if needed, but we use sqlite memory which should have migrations)
        // Note: The controller checks Schema::hasColumn. In-memory sqlite might need migrations run.
        // We rely on RefreshDatabase to run migrations.

        $response = $this->post(route('register.store'), $userData);

        $response->assertRedirect(route('otp.show'));
        $this->assertFalse(Auth::check(), 'User should not be authenticated after registration');
    }

    public function test_unverified_user_cannot_access_protected_routes()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get(route('profile.show'));

        // Should redirect to verification notice or login
        // Laravel's default behavior for 'verified' middleware is redirect to 'verification.notice'
        // But we might not have that route defined or it might be 'otp.show'
        
        // Let's check if it's NOT 200 OK
        $this->assertNotEquals(200, $response->status());
    }

    public function test_verified_user_can_access_protected_routes()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('profile.show'));

        $response->assertStatus(200);
    }
}
