<?php

namespace Tests\Feature\Security;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VerificationRouteTest extends TestCase
{
    use RefreshDatabase;

    public function test_unverified_user_redirects_to_verification_notice()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get(route('profile.show'));

        // Should redirect to verification.notice (which we mapped to OtpController::show)
        $response->assertRedirect(route('verification.notice'));
        $response->assertStatus(302); // Redirect
    }
}
