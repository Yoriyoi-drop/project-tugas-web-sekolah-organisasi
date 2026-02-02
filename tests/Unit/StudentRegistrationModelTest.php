<?php

namespace Tests\Unit;

use App\Models\StudentRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentRegistrationModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_registration_can_be_created()
    {
        $registration = $this->createStudentRegistration();

        $this->assertInstanceOf(StudentRegistration::class, $registration);
        $this->assertEquals('Test Student', $registration->name);
        $this->assertEquals('1234567890123456', $registration->nik);
        $this->assertEquals('test@example.com', $registration->email);
        $this->assertEquals('pending', $registration->status);
    }

    public function test_student_registration_has_fillable_attributes()
    {
        $fillable = (new StudentRegistration())->getFillable();
        
        $expected = [
            'name',
            'nik',
            'email',
            'birth_date',
            'birth_place',
            'gender',
            'address',
            'phone',
            'parent_name',
            'parent_phone',
            'previous_school',
            'desired_major',
            'status',
            'notes',
            'approved_at',
            'rejected_at',
            'approved_by',
            'rejected_by'
        ];

        $this->assertEquals($expected, $fillable);
    }

    public function test_student_registration_has_casts()
    {
        $casts = (new StudentRegistration())->getCasts();
        
        $this->assertArrayHasKey('birth_date', $casts);
        $this->assertArrayHasKey('approved_at', $casts);
        $this->assertArrayHasKey('rejected_at', $casts);
        $this->assertEquals('date', $casts['birth_date']);
        $this->assertEquals('datetime', $casts['approved_at']);
        $this->assertEquals('datetime', $casts['rejected_at']);
    }

    public function test_student_registration_scope_pending()
    {
        $pending = StudentRegistration::factory()->create(['status' => 'pending']);
        $approved = StudentRegistration::factory()->create(['status' => 'approved']);
        $rejected = StudentRegistration::factory()->create(['status' => 'rejected']);

        $pendingRegistrations = StudentRegistration::pending()->get();

        $this->assertCount(1, $pendingRegistrations);
        $this->assertEquals($pending->id, $pendingRegistrations->first()->id);
    }

    public function test_student_registration_scope_approved()
    {
        $pending = StudentRegistration::factory()->create(['status' => 'pending']);
        $approved = StudentRegistration::factory()->create(['status' => 'approved']);
        $rejected = StudentRegistration::factory()->create(['status' => 'rejected']);

        $approvedRegistrations = StudentRegistration::approved()->get();

        $this->assertCount(1, $approvedRegistrations);
        $this->assertEquals($approved->id, $approvedRegistrations->first()->id);
    }

    public function test_student_registration_scope_rejected()
    {
        $pending = StudentRegistration::factory()->create(['status' => 'pending']);
        $approved = StudentRegistration::factory()->create(['status' => 'approved']);
        $rejected = StudentRegistration::factory()->create(['status' => 'rejected']);

        $rejectedRegistrations = StudentRegistration::rejected()->get();

        $this->assertCount(1, $rejectedRegistrations);
        $this->assertEquals($rejected->id, $rejectedRegistrations->first()->id);
    }

    public function test_approve_method()
    {
        $registration = StudentRegistration::factory()->create(['status' => 'pending']);
        $user = User::factory()->create();

        $registration->approve($user, 'Test notes');

        $this->assertEquals('approved', $registration->status);
        $this->assertNotNull($registration->approved_at);
        $this->assertEquals($user->id, $registration->approved_by);
        $this->assertEquals('Test notes', $registration->notes);
    }

    public function test_reject_method()
    {
        $registration = StudentRegistration::factory()->create(['status' => 'pending']);
        $user = User::factory()->create();

        $registration->reject($user, 'Test rejection notes');

        $this->assertEquals('rejected', $registration->status);
        $this->assertNotNull($registration->rejected_at);
        $this->assertEquals($user->id, $registration->rejected_by);
        $this->assertEquals('Test rejection notes', $registration->notes);
    }

    public function test_is_pending_method()
    {
        $pending = StudentRegistration::factory()->create(['status' => 'pending']);
        $approved = StudentRegistration::factory()->create(['status' => 'approved']);

        $this->assertTrue($pending->isPending());
        $this->assertFalse($approved->isPending());
    }

    public function test_is_approved_method()
    {
        $pending = StudentRegistration::factory()->create(['status' => 'pending']);
        $approved = StudentRegistration::factory()->create(['status' => 'approved']);

        $this->assertFalse($pending->isApproved());
        $this->assertTrue($approved->isApproved());
    }

    public function test_is_rejected_method()
    {
        $pending = StudentRegistration::factory()->create(['status' => 'pending']);
        $rejected = StudentRegistration::factory()->create(['status' => 'rejected']);

        $this->assertFalse($pending->isRejected());
        $this->assertTrue($rejected->isRejected());
    }

    public function test_get_formatted_status_attribute()
    {
        $pending = StudentRegistration::factory()->create(['status' => 'pending']);
        $approved = StudentRegistration::factory()->create(['status' => 'approved']);
        $rejected = StudentRegistration::factory()->create(['status' => 'rejected']);

        $this->assertEquals('Menunggu Persetujuan', $pending->formatted_status);
        $this->assertEquals('Disetujui', $approved->formatted_status);
        $this->assertEquals('Ditolak', $rejected->formatted_status);
    }

    public function test_get_status_color_attribute()
    {
        $pending = StudentRegistration::factory()->create(['status' => 'pending']);
        $approved = StudentRegistration::factory()->create(['status' => 'approved']);
        $rejected = StudentRegistration::factory()->create(['status' => 'rejected']);

        $this->assertEquals('warning', $pending->status_color);
        $this->assertEquals('success', $approved->status_color);
        $this->assertEquals('danger', $rejected->status_color);
    }

    public function test_approved_by_relationship()
    {
        $user = User::factory()->create();
        $registration = StudentRegistration::factory()->create(['approved_by' => $user->id]);

        $this->assertInstanceOf(User::class, $registration->approvedBy);
        $this->assertEquals($user->id, $registration->approvedBy->id);
    }

    public function test_rejected_by_relationship()
    {
        $user = User::factory()->create();
        $registration = StudentRegistration::factory()->create(['rejected_by' => $user->id]);

        $this->assertInstanceOf(User::class, $registration->rejectedBy);
        $this->assertEquals($user->id, $registration->rejectedBy->id);
    }

    private function createStudentRegistration($overrides = [])
    {
        return StudentRegistration::create(array_merge([
            'name' => 'Test Student',
            'nik' => '1234567890123456',
            'email' => 'test@example.com',
            'birth_date' => '2005-01-01',
            'birth_place' => 'Jakarta',
            'gender' => 'male',
            'address' => 'Test Address',
            'phone' => '08123456789',
            'parent_name' => 'Parent Name',
            'parent_phone' => '08123456780',
            'previous_school' => 'Test School',
            'desired_major' => 'IPA',
            'status' => 'pending'
        ], $overrides));
    }
}
