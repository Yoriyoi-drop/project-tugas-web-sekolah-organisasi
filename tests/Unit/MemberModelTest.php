<?php

namespace Tests\Unit;

use App\Models\Member;
use App\Models\Organization;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_has_fillable_attributes()
    {
        $fillable = [
            'organization_id',
            'student_id',
            'teacher_id',
            'status',
            'role',
            'position',
            'period',
            'join_date',
            'end_date',
            'notes',
            'achievements',
            'skills'
        ];
        $this->assertEquals($fillable, (new Member())->getFillable());
    }

    public function test_member_has_casts()
    {
        $organization = Organization::factory()->create();
        $student = Student::factory()->create();
        
        $member = Member::create([
            'organization_id' => $organization->id,
            'student_id' => $student->id,
            'status' => 'active',
            'role' => 'member',
            'join_date' => '2023-01-01',
            'end_date' => '2024-01-01',
            'achievements' => ['Best Member Award'],
            'skills' => ['leadership', 'communication'],
            'is_active' => true
        ]);

        $this->assertIsString($member->status);
        $this->assertIsString($member->role);
        $this->assertInstanceOf(\Carbon\Carbon::class, $member->join_date);
        $this->assertInstanceOf(\Carbon\Carbon::class, $member->end_date);
        $this->assertIsArray($member->achievements);
        $this->assertIsArray($member->skills);
        $this->assertIsBool($member->is_active);
    }

    public function test_member_has_default_attributes()
    {
        $organization = Organization::factory()->create();
        $student = Student::factory()->create();
        
        $member = Member::create([
            'organization_id' => $organization->id,
            'student_id' => $student->id
        ]);

        $this->assertEquals('active', $member->status);
        $this->assertEquals('member', $member->role);
    }

    public function test_member_belongs_to_organization()
    {
        $organization = Organization::create([
            'name' => 'Test Organization',
            'description' => 'A test organization',
            'type' => 'club',
            'icon' => 'fa-users',
            'status' => 'active',
            'member_count' => 0
        ]);
        
        $student = Student::create([
            'name' => 'Test Student',
            'nis' => '123456789',
            'class' => 'XII-A',
            'email' => 'student@example.com'
        ]);
        
        $member = Member::create([
            'organization_id' => $organization->id,
            'student_id' => $student->id,
            'status' => 'active',
            'role' => 'member'
        ]);

        $this->assertTrue($member->organization->is($organization));
        $this->assertInstanceOf(Organization::class, $member->organization);
    }

    public function test_member_belongs_to_student()
    {
        $organization = Organization::create([
            'name' => 'Test Organization',
            'description' => 'A test organization',
            'type' => 'club',
            'icon' => 'fa-users',
            'status' => 'active',
            'member_count' => 0
        ]);
        
        $student = Student::create([
            'name' => 'Test Student',
            'nis' => '123456789',
            'class' => 'XII-A',
            'email' => 'student@example.com'
        ]);
        
        $member = Member::create([
            'organization_id' => $organization->id,
            'student_id' => $student->id,
            'status' => 'active',
            'role' => 'member'
        ]);

        $this->assertTrue($member->student->is($student));
        $this->assertInstanceOf(Student::class, $member->student);
    }

    public function test_member_belongs_to_teacher()
    {
        $organization = Organization::create([
            'name' => 'Test Organization',
            'description' => 'A test organization',
            'type' => 'club',
            'icon' => 'fa-users',
            'status' => 'active',
            'member_count' => 0
        ]);

        $student = Student::create([
            'name' => 'Dummy Student for Teacher Member',
            'nis' => 'DUMMY001',
            'class' => 'Teacher',
            'email' => 'dummy.teacher@example.com'
        ]);

        $teacher = Teacher::create([
            'name' => 'Test Teacher',
            'nip' => '987654321',
            'subject' => 'Mathematics',
            'email' => 'teacher@example.com'
        ]);

        $member = Member::create([
            'organization_id' => $organization->id,
            'student_id' => $student->id,
            'teacher_id' => $teacher->id,
            'status' => 'active',
            'role' => 'member'
        ]);

        $this->assertTrue($member->teacher->is($teacher));
        $this->assertInstanceOf(Teacher::class, $member->teacher);
    }

    public function test_active_scope()
    {
        $organization = Organization::create([
            'name' => 'Test Organization',
            'description' => 'A test organization',
            'type' => 'club',
            'icon' => 'fa-users',
            'status' => 'active',
            'member_count' => 0
        ]);
        
        $student = Student::create([
            'name' => 'Test Student',
            'nis' => '123456789',
            'class' => 'XII-A',
            'email' => 'student@example.com'
        ]);
        
        Member::create([
            'organization_id' => $organization->id,
            'student_id' => $student->id,
            'status' => 'active',
            'role' => 'member'
        ]);

        Member::create([
            'organization_id' => $organization->id,
            'student_id' => $student->id,
            'status' => 'inactive',
            'role' => 'member'
        ]);

        $activeMembers = Member::active()->get();
        
        $this->assertCount(1, $activeMembers);
        $this->assertEquals('active', $activeMembers->first()->status);
    }

    public function test_by_period_scope()
    {
        $organization = Organization::create([
            'name' => 'Test Organization',
            'description' => 'A test organization',
            'type' => 'club',
            'icon' => 'fa-users',
            'status' => 'active',
            'member_count' => 0
        ]);
        
        $student = Student::create([
            'name' => 'Test Student',
            'nis' => '123456789',
            'class' => 'XII-A',
            'email' => 'student@example.com'
        ]);
        
        Member::create([
            'organization_id' => $organization->id,
            'student_id' => $student->id,
            'status' => 'active',
            'role' => 'member',
            'period' => '2023-2024'
        ]);

        Member::create([
            'organization_id' => $organization->id,
            'student_id' => $student->id,
            'status' => 'active',
            'role' => 'member',
            'period' => '2022-2023'
        ]);

        $periodMembers = Member::byPeriod('2023-2024')->get();
        
        $this->assertCount(1, $periodMembers);
        $this->assertEquals('2023-2024', $periodMembers->first()->period);
    }

    public function test_leadership_scope()
    {
        $organization = Organization::create([
            'name' => 'Test Organization',
            'description' => 'A test organization',
            'type' => 'club',
            'icon' => 'fa-users',
            'status' => 'active',
            'member_count' => 0
        ]);
        
        $student = Student::create([
            'name' => 'Test Student',
            'nis' => '123456789',
            'class' => 'XII-A',
            'email' => 'student@example.com'
        ]);
        
        // Create leadership members
        Member::create([
            'organization_id' => $organization->id,
            'student_id' => $student->id,
            'status' => 'active',
            'role' => 'leader'
        ]);

        Member::create([
            'organization_id' => $organization->id,
            'student_id' => $student->id,
            'status' => 'active',
            'role' => 'secretary'
        ]);

        // Create regular member
        Member::create([
            'organization_id' => $organization->id,
            'student_id' => $student->id,
            'status' => 'active',
            'role' => 'member'
        ]);

        $leadershipMembers = Member::leadership()->get();

        $this->assertCount(2, $leadershipMembers);
        $this->assertContains('leader', $leadershipMembers->pluck('role')->toArray());
        $this->assertContains('secretary', $leadershipMembers->pluck('role')->toArray());
    }

    public function test_is_active_attribute()
    {
        $organization = Organization::create([
            'name' => 'Test Organization',
            'description' => 'A test organization',
            'type' => 'club',
            'icon' => 'fa-users',
            'status' => 'active',
            'member_count' => 0
        ]);
        
        $student = Student::create([
            'name' => 'Test Student',
            'nis' => '123456789',
            'class' => 'XII-A',
            'email' => 'student@example.com'
        ]);
        
        // Active member with no end date
        $activeMember = Member::create([
            'organization_id' => $organization->id,
            'student_id' => $student->id,
            'status' => 'active',
            'role' => 'member'
        ]);

        $this->assertTrue($activeMember->is_active);

        // Active member with future end date
        $futureEndDateMember = Member::create([
            'organization_id' => $organization->id,
            'student_id' => $student->id,
            'status' => 'active',
            'role' => 'member',
            'end_date' => now()->addMonth()
        ]);

        $this->assertTrue($futureEndDateMember->is_active);

        // Active member with past end date
        $pastEndDateMember = Member::create([
            'organization_id' => $organization->id,
            'student_id' => $student->id,
            'status' => 'active',
            'role' => 'member',
            'end_date' => now()->subMonth()
        ]);

        $this->assertFalse($pastEndDateMember->is_active);
    }

    public function test_full_name_attribute()
    {
        $organization = Organization::create([
            'name' => 'Test Organization',
            'description' => 'A test organization',
            'type' => 'club',
            'icon' => 'fa-users',
            'status' => 'active',
            'member_count' => 0
        ]);

        $student = Student::create([
            'name' => 'John Doe',
            'nis' => '123456789',
            'class' => 'XII-A',
            'email' => 'student@example.com'
        ]);

        $teacherStudent = Student::create([
            'name' => 'Jane Smith (Teacher)',
            'nis' => 'TEACHER001',
            'class' => 'Teacher',
            'email' => 'jane.teacher@example.com'
        ]);

        $teacher = Teacher::create([
            'name' => 'Jane Smith',
            'nip' => '987654321',
            'subject' => 'Mathematics',
            'email' => 'teacher@example.com'
        ]);

        $studentMember = Member::create([
            'organization_id' => $organization->id,
            'student_id' => $student->id,
            'status' => 'active',
            'role' => 'member'
        ]);

        $teacherMember = Member::create([
            'organization_id' => $organization->id,
            'student_id' => $teacherStudent->id,
            'teacher_id' => $teacher->id,
            'status' => 'active',
            'role' => 'member'
        ]);

        $this->assertEquals('John Doe', $studentMember->full_name);
        $this->assertEquals('Jane Smith (Teacher)', $teacherMember->full_name);
    }

    public function test_member_type_attribute()
    {
        $organization = Organization::create([
            'name' => 'Test Organization',
            'description' => 'A test organization',
            'type' => 'club',
            'icon' => 'fa-users',
            'status' => 'active',
            'member_count' => 0
        ]);

        $student = Student::create([
            'name' => 'John Doe',
            'nis' => '123456789',
            'class' => 'XII-A',
            'email' => 'student@example.com'
        ]);

        $teacherStudent = Student::create([
            'name' => 'Jane Smith (Teacher)',
            'nis' => 'TEACHER002',
            'class' => 'Teacher',
            'email' => 'jane.teacher2@example.com'
        ]);

        $teacher = Teacher::create([
            'name' => 'Jane Smith',
            'nip' => '987654321',
            'subject' => 'Mathematics',
            'email' => 'teacher@example.com'
        ]);

        $studentMember = Member::create([
            'organization_id' => $organization->id,
            'student_id' => $student->id,
            'status' => 'active',
            'role' => 'member'
        ]);

        $teacherMember = Member::create([
            'organization_id' => $organization->id,
            'student_id' => $teacherStudent->id,
            'teacher_id' => $teacher->id,
            'status' => 'active',
            'role' => 'member'
        ]);

        $this->assertEquals('student', $studentMember->member_type);
        $this->assertEquals('student', $teacherMember->member_type);
    }

    public function test_promote_to_role_method()
    {
        $organization = Organization::create([
            'name' => 'Test Organization',
            'description' => 'A test organization',
            'type' => 'club',
            'icon' => 'fa-users',
            'status' => 'active',
            'member_count' => 0
        ]);
        
        $student = Student::create([
            'name' => 'John Doe',
            'nis' => '123456789',
            'class' => 'XII-A',
            'email' => 'student@example.com'
        ]);
        
        $member = Member::create([
            'organization_id' => $organization->id,
            'student_id' => $student->id,
            'status' => 'active',
            'role' => 'member'
        ]);

        $member->promoteToRole('leader', 'Ketua');

        $freshMember = $member->fresh();
        $this->assertEquals('leader', $freshMember->role);
        $this->assertEquals('Ketua', $freshMember->position);
    }

    public function test_change_status_method()
    {
        $organization = Organization::create([
            'name' => 'Test Organization',
            'description' => 'A test organization',
            'type' => 'club',
            'icon' => 'fa-users',
            'status' => 'active',
            'member_count' => 0
        ]);
        
        $student = Student::create([
            'name' => 'John Doe',
            'nis' => '123456789',
            'class' => 'XII-A',
            'email' => 'student@example.com'
        ]);
        
        $member = Member::create([
            'organization_id' => $organization->id,
            'student_id' => $student->id,
            'status' => 'active',
            'role' => 'member'
        ]);

        $member->changeStatus('inactive');

        $freshMember = $member->fresh();
        $this->assertEquals('inactive', $freshMember->status);
        $this->assertNotNull($freshMember->end_date);
    }

    public function test_add_achievement_method()
    {
        $organization = Organization::create([
            'name' => 'Test Organization',
            'description' => 'A test organization',
            'type' => 'club',
            'icon' => 'fa-users',
            'status' => 'active',
            'member_count' => 0
        ]);
        
        $student = Student::create([
            'name' => 'John Doe',
            'nis' => '123456789',
            'class' => 'XII-A',
            'email' => 'student@example.com'
        ]);
        
        $member = Member::create([
            'organization_id' => $organization->id,
            'student_id' => $student->id,
            'status' => 'active',
            'role' => 'member'
        ]);

        $member->addAchievement('Best Member Award');

        $freshMember = $member->fresh();
        $this->assertCount(1, $freshMember->achievements);
        $this->assertEquals('Best Member Award', $freshMember->achievements[0]['title']);
        $this->assertEquals(now()->toDateString(), $freshMember->achievements[0]['date']);
    }

    public function test_add_skill_method()
    {
        $organization = Organization::create([
            'name' => 'Test Organization',
            'description' => 'A test organization',
            'type' => 'club',
            'icon' => 'fa-users',
            'status' => 'active',
            'member_count' => 0
        ]);
        
        $student = Student::create([
            'name' => 'John Doe',
            'nis' => '123456789',
            'class' => 'XII-A',
            'email' => 'student@example.com'
        ]);
        
        $member = Member::create([
            'organization_id' => $organization->id,
            'student_id' => $student->id,
            'status' => 'active',
            'role' => 'member'
        ]);

        $member->addSkill('leadership');

        $freshMember = $member->fresh();
        $this->assertCount(1, $freshMember->skills);
        $this->assertContains('leadership', $freshMember->skills);

        // Add the same skill again - should not duplicate
        $member->addSkill('leadership');
        $freshMember = $member->fresh();
        $this->assertCount(1, $freshMember->skills);
        $this->assertContains('leadership', $freshMember->skills);
    }

    public function test_create_member()
    {
        $organization = Organization::create([
            'name' => 'Test Organization',
            'description' => 'A test organization',
            'type' => 'club',
            'icon' => 'fa-users',
            'status' => 'active',
            'member_count' => 0
        ]);
        
        $student = Student::create([
            'name' => 'John Doe',
            'nis' => '123456789',
            'class' => 'XII-A',
            'email' => 'student@example.com'
        ]);

        $member = Member::create([
            'organization_id' => $organization->id,
            'student_id' => $student->id,
            'status' => 'active',
            'role' => 'member'
        ]);

        $this->assertDatabaseHas('members', [
            'organization_id' => $organization->id,
            'student_id' => $student->id,
            'status' => 'active',
            'role' => 'member'
        ]);

        $this->assertEquals($organization->id, $member->organization_id);
        $this->assertEquals($student->id, $member->student_id);
        $this->assertEquals('active', $member->status);
        $this->assertEquals('member', $member->role);
    }

    public function test_update_member()
    {
        $organization = Organization::create([
            'name' => 'Test Organization',
            'description' => 'A test organization',
            'type' => 'club',
            'icon' => 'fa-users',
            'status' => 'active',
            'member_count' => 0
        ]);
        
        $student = Student::create([
            'name' => 'John Doe',
            'nis' => '123456789',
            'class' => 'XII-A',
            'email' => 'student@example.com'
        ]);

        $member = Member::create([
            'organization_id' => $organization->id,
            'student_id' => $student->id,
            'status' => 'active',
            'role' => 'member'
        ]);

        $updated = $member->update([
            'status' => 'inactive',
            'role' => 'member'
        ]);

        $this->assertTrue($updated);
        $this->assertDatabaseHas('members', [
            'organization_id' => $organization->id,
            'status' => 'inactive',
            'role' => 'member'
        ]);
        $this->assertDatabaseMissing('members', [
            'status' => 'active'
        ]);
    }

    public function test_delete_member()
    {
        $organization = Organization::create([
            'name' => 'Test Organization',
            'description' => 'A test organization',
            'type' => 'club',
            'icon' => 'fa-users',
            'status' => 'active',
            'member_count' => 1
        ]);
        
        $student = Student::create([
            'name' => 'John Doe',
            'nis' => '123456789',
            'class' => 'XII-A',
            'email' => 'student@example.com'
        ]);

        $member = Member::create([
            'organization_id' => $organization->id,
            'student_id' => $student->id,
            'status' => 'active',
            'role' => 'member'
        ]);

        $deleted = $member->delete();

        $this->assertTrue($deleted);
        $this->assertNotNull($member->fresh()->deleted_at);
    }
}