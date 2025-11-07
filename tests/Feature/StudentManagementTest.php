<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_students_list()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $student = Student::factory()->create();

        $response = $this->actingAs($admin)->get(route('admin.students.index'));

        $response->assertOk()
                ->assertViewIs('admin.students.index')
                ->assertSee($student->name)
                ->assertSee($student->nis);
    }

    public function test_admin_can_create_student()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->post(route('admin.students.store'), [
            'name' => 'Test Student',
            'nis' => '123456',
            'email' => 'student@test.com',
            'phone' => '08123456789',
            'class' => 'X IPA 1',
            'address' => 'Test Address'
        ]);

        $response->assertRedirect(route('admin.students.index'));
        $this->assertDatabaseHas('students', [
            'name' => 'Test Student',
            'nis' => '123456',
            'email' => 'student@test.com'
        ]);
    }

    public function test_admin_can_update_student()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $student = Student::factory()->create();

        $response = $this->actingAs($admin)->put(route('admin.students.update', $student), [
            'name' => 'Updated Name',
            'nis' => $student->nis,
            'email' => 'updated@test.com',
            'phone' => '08123456789',
            'class' => 'XI IPA 1',
            'address' => 'Updated Address'
        ]);

        $response->assertRedirect(route('admin.students.index'));
        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'name' => 'Updated Name',
            'email' => 'updated@test.com'
        ]);
    }

    public function test_admin_can_delete_student()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $student = Student::factory()->create();

        $response = $this->actingAs($admin)->delete(route('admin.students.destroy', $student));

        $response->assertRedirect(route('admin.students.index'));
        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }
}
