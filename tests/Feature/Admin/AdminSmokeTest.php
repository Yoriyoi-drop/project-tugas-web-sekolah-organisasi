<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminSmokeTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a verified admin user
        $this->admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Helper to test a route
     */
    protected function checkRoute($routeName)
    {
        $response = $this->actingAs($this->admin)->get(route($routeName));
        $response->assertStatus(200);
    }

    public function test_admin_dashboard_loads()
    {
        $this->checkRoute('admin.dashboard');
    }

    public function test_admin_posts_index_loads()
    {
        $this->checkRoute('admin.posts.index');
    }

    public function test_admin_organizations_index_loads()
    {
        $this->checkRoute('admin.organizations.index');
    }

    public function test_admin_activities_index_loads()
    {
        $this->checkRoute('admin.activities.index');
    }

    public function test_admin_statistics_index_loads()
    {
        $this->checkRoute('admin.statistics.index');
    }

    public function test_admin_students_index_loads()
    {
        $this->checkRoute('admin.students.index');
    }

    public function test_admin_teachers_index_loads()
    {
        $this->checkRoute('admin.teachers.index');
    }

    public function test_admin_facilities_index_loads()
    {
        $this->checkRoute('admin.facilities.index');
    }

    public function test_admin_messages_index_loads()
    {
        $this->checkRoute('admin.messages.index');
    }

    public function test_admin_settings_index_loads()
    {
        $this->checkRoute('admin.settings.index');
    }

    public function test_admin_registrations_index_loads()
    {
        $this->checkRoute('admin.registrations.index');
    }

    public function test_admin_users_index_loads()
    {
        $this->checkRoute('admin.users.index');
    }

    public function test_admin_security_audit_loads()
    {
        $this->checkRoute('admin.security.audit');
    }
}
