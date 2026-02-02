<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Organization;
use App\Models\Member;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

class OrganizationManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        // Disable CSRF token verification for this test class
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }

    public function test_admin_can_create_organization()
    {
        // Visit the create page to set CSRF token in session
        $this->actingAs($this->admin)->get(route('admin.organizations.create'));

        $orgData = [
            'name' => 'Test Organization',
            'type' => 'Extracurricular',
            'tagline' => 'Test Tagline',
            'description' => 'Test Description',
            'icon' => 'fa-users',
            'color' => 'primary',
            'is_active' => true,
            'order' => 1,
            '_token' => $this->app['session']->token()
        ];

        $response = $this->actingAs($this->admin)
                        ->post(route('admin.organizations.store'), $orgData);

        $response->assertRedirect(route('admin.organizations.index'));

        $this->assertDatabaseHas('organizations', [
            'name' => 'Test Organization',
            'type' => 'Extracurricular'
        ]);
    }

    public function test_admin_can_add_member_to_organization()
    {
        $organization = Organization::factory()->create();
        $student = Student::factory()->create();

        // Visit the organization page to set CSRF token in session
        $this->actingAs($this->admin)->get(route('admin.organizations.show', $organization));

        $memberData = [
            'member_type' => 'student',
            'student_id' => $student->id,
            'role' => 'member',
            'period' => '2024/2025',
            'join_date' => now()->format('Y-m-d'),
            '_token' => $this->app['session']->token()
        ];

        $response = $this->actingAs($this->admin)
                        ->post(route('admin.organizations.members.store', $organization), $memberData);

        $response->assertRedirect(route('admin.organizations.members.index', $organization));

        $this->assertDatabaseHas('members', [
            'organization_id' => $organization->id,
            'student_id' => $student->id,
            'role' => 'member'
        ]);
    }

    public function test_admin_cannot_add_duplicate_member()
    {
        $organization = Organization::factory()->create();
        $student = Student::factory()->create();

        // Add first member using the same method as the store controller
        $organization->members()->create([
            'student_id' => $student->id,
            'role' => 'member',
            'period' => '2024/2025',
            'status' => 'active',
            'join_date' => now()
        ]);

        // Visit the create page to set CSRF token in session
        $this->actingAs($this->admin)->get(route('admin.organizations.members.create', $organization));

        $memberData = [
            'member_type' => 'student',
            'student_id' => $student->id,
            'role' => 'member',
            'period' => '2024/2025',
            'join_date' => now()->format('Y-m-d'),
            '_token' => $this->app['session']->token()
        ];

        $response = $this->actingAs($this->admin)
                        ->post(route('admin.organizations.members.store', $organization), $memberData);

        $response->assertSessionHasErrors(['duplicate']);
    }

    public function test_admin_can_update_member_role()
    {
        $organization = Organization::factory()->create();
        $student = Student::factory()->create();
        $member = $organization->addMember($student->id, 'member');

        // Visit the edit page to set CSRF token in session
        $this->actingAs($this->admin)->get(route('admin.organizations.members.edit', [$organization, $member]));

        $updateData = [
            'role' => 'leader',
            'status' => 'active',
            'period' => '2024/2025',
            '_token' => $this->app['session']->token()
        ];

        $response = $this->actingAs($this->admin)
                        ->put(route('admin.organizations.members.update', [$organization, $member]), $updateData);

        $response->assertRedirect(route('admin.organizations.members.index', $organization));

        $this->assertDatabaseHas('members', [
            'id' => $member->id,
            'role' => 'leader'
        ]);
    }

    public function test_admin_can_remove_member_from_organization()
    {
        $organization = Organization::factory()->create();
        $student = Student::factory()->create();
        $member = $organization->addMember($student->id, 'member');

        // Visit the members index page to set CSRF token in session
        $this->actingAs($this->admin)->get(route('admin.organizations.members.index', $organization));

        $response = $this->actingAs($this->admin)
                        ->delete(route('admin.organizations.members.destroy', [$organization, $member]), [
                            '_token' => $this->app['session']->token()
                        ]);

        $response->assertRedirect(route('admin.organizations.members.index', $organization));

        $this->assertSoftDeleted('members', [
            'id' => $member->id
        ]);
    }

    public function test_non_admin_cannot_access_organization_management()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $organization = Organization::factory()->create();

        $response = $this->actingAs($user)
                        ->get(route('admin.organizations.index'));

        $response->assertForbidden();
    }

    public function test_organization_member_count_updates_correctly()
    {
        $organization = Organization::factory()->create();
        $students = Student::factory()->count(3)->create();

        foreach ($students as $student) {
            $organization->addMember($student->id, 'member');
        }

        $organization->refresh();
        
        $this->assertEquals(3, $organization->member_count);
    }
}
