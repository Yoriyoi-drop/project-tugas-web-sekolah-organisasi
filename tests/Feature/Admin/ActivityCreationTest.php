<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_activity()
    {
        $admin = User::factory()->create(['is_admin' => true, 'email_verified_at' => now()]);
        
        $activityData = [
            'title' => 'New Activity',
            'description' => 'Description',
            'date' => now()->addDay()->format('Y-m-d'),
            'location' => 'Hall',
            'category' => 'General', // Required field
        ];

        $response = $this->actingAs($admin)->post(route('admin.activities.store'), $activityData);

        $response->assertRedirect(route('admin.activities.index'));
        $this->assertDatabaseHas('activities', ['title' => 'New Activity']);
    }

    public function test_activity_creation_requires_category()
    {
        $admin = User::factory()->create(['is_admin' => true, 'email_verified_at' => now()]);
        
        $activityData = [
            'title' => 'New Activity',
            'description' => 'Description',
            'date' => now()->addDay()->format('Y-m-d'),
            'location' => 'Hall',
            // Missing category
        ];

        $response = $this->actingAs($admin)->post(route('admin.activities.store'), $activityData);

        $response->assertSessionHasErrors('category');
    }
}
