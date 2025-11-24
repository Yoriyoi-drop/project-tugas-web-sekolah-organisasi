<?php

namespace Tests\Feature\Admin;

use App\Models\Activity;
use App\Models\Category;
use App\Models\Post;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Facility;
use App\Models\Contact;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminFullFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        // Create an admin user (is_admin = true)
        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    /** Helper to act as admin */
    protected function actingAsAdmin()
    {
        return $this->actingAs($this->admin);
    }

    public function test_admin_dashboard_loads()
    {
        $this->actingAsAdmin()->get(route('admin.dashboard'))
            ->assertStatus(200);
    }

    // Posts (already covered in previous test, but repeat for completeness)
    public function test_admin_can_create_post()
    {
        $category = Category::factory()->create();
        $postData = [
            'title' => 'Full Test Post',
            'excerpt' => 'Excerpt content',
            'content' => 'Full content body',
            'category' => $category->name,
            'status' => 'published',
            'image' => UploadedFile::fake()->image('post.jpg'),
        ];
        $response = $this->actingAsAdmin()->post(route('admin.posts.store'), $postData);
        $response->assertRedirect(route('admin.posts.index'));
        $this->assertDatabaseHas('posts', ['title' => 'Full Test Post']);
    }

    public function test_admin_can_manage_organizations()
    {
        $orgData = [
            'name' => 'Org Test',
            'type' => 'Test Type',
            'description' => 'Desc',
            'icon' => 'fa-test',
            'is_active' => true,
            'order' => 1,
        ];
        $response = $this->actingAsAdmin()->post(route('admin.organizations.store'), $orgData);
        $response->assertRedirect(route('admin.organizations.index'));
        $this->assertDatabaseHas('organizations', ['name' => 'Org Test']);
    }

    public function test_admin_can_manage_activities()
    {
        $activity = Activity::factory()->create();
        $this->actingAsAdmin()->get(route('admin.activities.show', $activity))
            ->assertStatus(200);
        $this->actingAsAdmin()->delete(route('admin.activities.destroy', $activity))
            ->assertRedirect(route('admin.activities.index'));
    }

    public function test_admin_can_manage_statistics()
    {
        $this->actingAsAdmin()->get(route('admin.statistics.index'))
            ->assertStatus(200);
    }

    public function test_admin_can_manage_students()
    {
        $studentData = [
            'name' => 'Student Test',
            'nis' => '123456',
            'email' => 'student@test.com',
            'phone' => '08123456789',
            'class' => '10A',
            'address' => 'Jl. Test',
        ];
        $response = $this->actingAsAdmin()->post(route('admin.students.store'), $studentData);
        $response->assertRedirect(route('admin.students.index'));
        $this->assertDatabaseHas('students', ['email' => 'student@test.com']);
    }

    public function test_admin_can_manage_teachers()
    {
        $teacher = Teacher::factory()->create();
        $this->actingAsAdmin()->get(route('admin.teachers.show', $teacher))
            ->assertStatus(200);
    }

    public function test_admin_can_manage_facilities()
    {
        $facility = Facility::factory()->create();
        $this->actingAsAdmin()->get(route('admin.facilities.show', $facility))
            ->assertStatus(200);
    }

    public function test_admin_can_manage_messages()
    {
        $message = Contact::factory()->create();
        $this->actingAsAdmin()->get(route('admin.messages.show', $message))
            ->assertStatus(200);
        $this->actingAsAdmin()->delete(route('admin.messages.destroy', $message))
            ->assertRedirect(route('admin.messages.index'));
    }

    public function test_admin_can_update_settings()
    {
        $settingsData = [
            'site_name' => 'New Site',
            'site_description' => 'Desc',
            'contact_email' => 'info@example.com',
            'contact_phone' => '+62 812 3456',
            'address' => 'Jl. Baru',
        ];
        $response = $this->actingAsAdmin()->put(route('admin.settings.update'), $settingsData);
        $response->assertRedirect(route('admin.settings.index'));
    }

    public function test_admin_can_manage_registrations()
    {
        $registration = Registration::factory()->create();
        $this->actingAsAdmin()->get(route('admin.registrations.show', $registration))
            ->assertStatus(200);
        $this->actingAsAdmin()->patch(route('admin.registrations.update-status', $registration), ['status' => 'approved'])
            ->assertRedirect(route('admin.registrations.index'));
    }

    public function test_admin_can_manage_users()
    {
        $userData = [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'is_admin' => false,
            'nik' => '1234567890123456',
            'nis' => '1234567890',
        ];
        $response = $this->actingAsAdmin()->post(route('admin.users.store'), $userData);
        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['email' => 'newuser@example.com']);
    }

    public function test_admin_can_access_security_audit()
    {
        $this->actingAsAdmin()->get(route('admin.security.audit'))
            ->assertStatus(200);
        $this->actingAsAdmin()->get(route('admin.security.export'))
            ->assertStatus(200);
    }
}
?>
