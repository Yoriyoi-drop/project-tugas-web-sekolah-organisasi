<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Student;
use App\Models\Organization;
use App\Models\Setting;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

class AdminFeatureTest extends TestCase
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
    }

    public function test_admin_can_create_post()
    {
        $postData = [
            'title' => 'New Blog Post',
            'excerpt' => 'This is a short excerpt.',
            'content' => 'This is the content of the new post.',
            'category' => 'News', // Controller expects string
            'status' => 'published',
            'image' => UploadedFile::fake()->image('post.jpg')
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.posts.store'), $postData);

        $response->assertRedirect(route('admin.posts.index'));
        $this->assertDatabaseHas('posts', ['title' => 'New Blog Post']);
    }

    public function test_admin_can_create_organization()
    {
        $orgData = [
            'name' => 'New Organization',
            'type' => 'Extracurricular',
            'tagline' => 'Best Org',
            'description' => 'Description of the org',
            'icon' => 'fa-users', // Added required icon
            'is_active' => true,
            'order' => 1
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.organizations.store'), $orgData);

        $response->assertRedirect(route('admin.organizations.index'));
        $this->assertDatabaseHas('organizations', ['name' => 'New Organization']);
    }

    public function test_admin_can_create_student()
    {
        $studentData = [
            'name' => 'John Doe',
            'nis' => '12345678',
            'class' => 'XII IPA 1',
            'email' => 'john@example.com',
            'phone' => '08123456789',
            'address' => 'Jl. Test No. 1'
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.students.store'), $studentData);

        $response->assertRedirect(route('admin.students.index'));
        $this->assertDatabaseHas('students', ['name' => 'John Doe', 'nis' => '12345678']);
    }

    public function test_admin_can_update_settings()
    {
        $settingsData = [
            'site_name' => 'New Site Name',
            'site_description' => 'New Description',
            'contact_email' => 'new@example.com',
            'contact_phone' => '0800000000',
            'address' => 'New Address'
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.settings.update'), $settingsData);

        $response->assertRedirect(route('admin.settings.index'));
        
        // Verify setting is saved (assuming Setting model has a get method or we check DB table)
        $this->assertEquals('New Site Name', Setting::get('site_name'));
    }
}
