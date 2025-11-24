<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_dashboard()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_access_dashboard()
    {
        $user = User::factory()->create([
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_unverified_admin_cannot_access_dashboard()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        // Should redirect to verification notice (otp.show)
        $response->assertRedirect(route('verification.notice'));
    }
}
