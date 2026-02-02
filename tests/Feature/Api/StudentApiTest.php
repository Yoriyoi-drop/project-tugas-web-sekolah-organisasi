<?php

namespace Tests\Feature\Api;

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\Response;

class StudentApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_student_via_api()
    {
        $user = \App\Models\User::factory()->create();

        $studentData = [
            'name' => 'Test Student',
            'nis' => '123456789',
            'email' => 'test@student.com',
            'phone' => '08123456789',
            'class' => 'XII IPA 1'
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v2/students', $studentData);

        $response->assertStatus(Response::HTTP_CREATED)
                ->assertJson([
                    'success' => true,
                    'message' => 'Student berhasil ditambahkan'
                ]);

        $this->assertDatabaseHas('students', [
            'name' => 'Test Student',
            'nis' => '123456789',
            'email' => 'test@student.com'
        ]);
    }

    public function test_cannot_create_student_with_duplicate_nis()
    {
        $user = \App\Models\User::factory()->create();
        Student::factory()->create(['nis' => '123456789']);

        $studentData = [
            'name' => 'Test Student',
            'nis' => '123456789',
            'email' => 'different@student.com',
            'class' => 'XII IPA 1'
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v2/students', $studentData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->assertJsonValidationErrors(['nis']);
    }

    public function test_cannot_create_student_with_invalid_email()
    {
        $user = \App\Models\User::factory()->create();
        $studentData = [
            'name' => 'Test Student',
            'nis' => '123456789',
            'email' => 'invalid-email',
            'class' => 'XII IPA 1'
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v2/students', $studentData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->assertJsonValidationErrors(['email']);
    }

    public function test_can_update_student_via_api()
    {
        $user = \App\Models\User::factory()->create();
        $student = Student::factory()->create();

        $updateData = [
            'name' => 'Updated Student Name',
            'nis' => $student->nis, // Include required field
            'email' => $student->email, // Include required field
            'class' => 'XII IPA 2'
        ];

        $response = $this->actingAs($user, 'sanctum')->putJson("/api/v2/students/{$student->id}", $updateData);

        $response->assertStatus(Response::HTTP_OK)
                ->assertJson([
                    'success' => true,
                    'message' => 'Student berhasil diperbarui'
                ]);

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'name' => 'Updated Student Name',
            'class' => 'XII IPA 2'
        ]);
    }

    public function test_can_delete_student_via_api()
    {
        $user = \App\Models\User::factory()->create();
        $student = Student::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/v2/students/{$student->id}");

        $response->assertStatus(Response::HTTP_OK)
                ->assertJson([
                    'success' => true,
                    'message' => 'Student berhasil dihapus'
                ]);

        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }

    public function test_can_list_students_via_api()
    {
        $user = \App\Models\User::factory()->create();
        Student::factory()->count(5)->create();

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/v2/students');

        $response->assertStatus(Response::HTTP_OK)
                ->assertJson([
                    'success' => true
                ])
                ->assertJsonCount(5, 'data');
    }

    public function test_api_returns_404_for_nonexistent_student()
    {
        $user = \App\Models\User::factory()->create();
        $response = $this->actingAs($user, 'sanctum')->getJson('/api/v2/students/999');

        $response->assertStatus(Response::HTTP_NOT_FOUND)
                ->assertJson([
                    'success' => false
                ])
                ->assertJsonFragment([
                    'message' => 'Student tidak ditemukan'
                ]);
    }
}
