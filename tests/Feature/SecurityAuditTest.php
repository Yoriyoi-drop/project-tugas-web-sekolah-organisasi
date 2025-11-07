<?php

use App\Models\User;
use App\Models\Role;
use App\Models\Ability;
use App\Models\SecurityLog;
use App\Services\SecurityService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create admin role and ability
    $this->manageSecurityAbility = Ability::create([
        'name' => 'Manage Security',
        'slug' => 'manage_security',
        'description' => 'Can manage security settings and view audit logs',
    ]);

    $this->adminRole = Role::create([
        'name' => 'Admin',
        'slug' => 'admin',
        'description' => 'Administrator',
    ]);

    $this->adminRole->abilities()->attach($this->manageSecurityAbility);

    // Create and authenticate admin user
    $this->admin = User::factory()->create(['is_admin' => true]);
    $this->admin->roles()->attach($this->adminRole);

    // Create regular user
    $this->user = User::factory()->create(['is_admin' => false]);

    // Create some security logs
    for ($i = 0; $i < 5; $i++) {
        SecurityLog::create([
            'user_id' => $this->user->id,
            'action' => 'otp_verify',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Test Browser',
            'data' => ['status' => $i % 2 ? 'success' : 'error', 'description' => 'OTP verification attempt'],
            'risk_level' => $i % 3 ? 'medium' : 'high',
        ]);
    }
});

test('unauthorized users cannot access security audit', function () {
    $response = $this->actingAs($this->user)
        ->get(route('admin.security.audit'));

    $response->assertStatus(403);
});

test('authorized admin can access security audit', function () {
    $response = $this->actingAs($this->admin)
        ->get(route('admin.security.audit'));

    $response->assertStatus(200)
        ->assertViewIs('admin.security.audit')
        ->assertViewHas(['logs', 'stats', 'eventTypes']);
});

test('security audit dashboard shows correct statistics', function () {
    $response = $this->actingAs($this->admin)
        ->get(route('admin.security.audit'));

    $stats = $response->viewData('stats');

    expect($stats['total_otp_attempts'])->toBe(5)
        ->and($stats['failed_verifications'])->toBe(2)
        ->and($stats['high_risk_events'])->toBe(2);
});

test('security audit logs can be filtered', function () {
    $response = $this->actingAs($this->admin)
        ->get(route('admin.security.audit', [
            'status' => 'error',
            'risk_level' => 'high'
        ]));

    $logs = $response->viewData('logs');

    expect($logs)->toBeInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class)
        ->and($logs->total())->toBeGreaterThan(0);
});

test('security logs can be exported to csv', function () {
    $response = $this->actingAs($this->admin)
        ->get(route('admin.security.export'));
    $response->assertStatus(200)
        // Content-Disposition should match exactly
        ->assertHeader('Content-Disposition', 'attachment; filename="security-logs.csv"');

    // Allow the Content-Type to include a charset (test environment may append it).
    $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
});
