<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_teachers_list()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $teacher = Teacher::create([
            'name' => 'Guru Test',
            'email' => 'guru@test.com',
            'phone' => '081234567890',
            'subject' => 'Mathematics',
            'qualification' => 'S.Pd'
        ]);

        $response = $this->actingAs($admin)->get(route('admin.teachers.index'));

        $response->assertOk()
                 ->assertViewIs('admin.teachers.index')
                 ->assertSee($teacher->name)
                 ->assertSee($teacher->email);
    }

    public function test_admin_can_create_teacher()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->post(route('admin.teachers.store'), [
            'name' => 'New Guru',
            'email' => 'newguru@test.com',
            'phone' => '081300000000',
            'subject' => 'Physics',
            'qualification' => 'M.Sc'
        ]);

        $response->assertRedirect(route('admin.teachers.index'));

        $this->assertDatabaseHas('teachers', [
            'name' => 'New Guru',
            'email' => 'newguru@test.com'
        ]);
    }

    public function test_admin_can_update_teacher()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $teacher = Teacher::create([
            'name' => 'To Update',
            'email' => 'toupdate@test.com',
            'phone' => '081299999999',
            'subject' => 'History',
            'qualification' => 'S.Hum'
        ]);

        $response = $this->actingAs($admin)->put(route('admin.teachers.update', $teacher), [
            'name' => 'Updated Guru',
            'email' => 'updatedguru@test.com',
            'phone' => '081211111111',
            'subject' => 'Geography',
            'qualification' => 'M.A'
        ]);

        $response->assertRedirect(route('admin.teachers.index'));

        $this->assertDatabaseHas('teachers', [
            'id' => $teacher->id,
            'name' => 'Updated Guru',
            'email' => 'updatedguru@test.com'
        ]);
    }

    public function test_admin_can_delete_teacher()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $teacher = Teacher::create([
            'name' => 'To Delete',
            'email' => 'todelete@test.com',
            'phone' => '081288888888',
            'subject' => 'Art',
            'qualification' => 'S.Pd'
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.teachers.destroy', $teacher));

        $response->assertRedirect(route('admin.teachers.index'));

        $this->assertDatabaseMissing('teachers', ['id' => $teacher->id]);
    }
}
