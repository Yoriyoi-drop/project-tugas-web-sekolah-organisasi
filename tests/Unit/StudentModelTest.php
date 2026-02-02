<?php

namespace Tests\Unit;

use App\Models\Student;
use App\Models\User;
use App\Models\Member;
use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_has_memberships_relationship()
    {
        $student = Student::factory()->create();
        $organization = Organization::factory()->create();
        
        $member = $organization->addMember($student->id, 'member');

        $this->assertCount(1, $student->memberships);
        $this->assertInstanceOf(Member::class, $student->memberships->first());
    }

    public function test_student_can_get_name_with_nis()
    {
        $student = Student::factory()->create([
            'name' => 'John Doe',
            'nis' => '1234567890'
        ]);

        $expectedName = $student->name . ' (' . $student->nis . ')';
        $this->assertEquals($expectedName, $student->name . ' (' . $student->nis . ')');
    }

    public function test_student_can_check_if_member_of_organization()
    {
        $student = Student::factory()->create();
        $organization = Organization::factory()->create();

        $this->assertFalse($student->hasMembership($organization->id));

        $organization->addMember($student->id, 'member');

        $this->assertTrue($student->hasMembership($organization->id));
    }

    public function test_student_gets_current_memberships()
    {
        $student = Student::factory()->create();
        $organization = Organization::factory()->create();
        
        // Add member to organization
        $organization->addMember($student->id, 'member');

        $currentMemberships = $student->activeMemberships;

        $this->assertCount(1, $currentMemberships);
        $this->assertEquals($organization->id, $currentMemberships->first()->organization_id);
    }

    public function test_student_joins_and_leaves_organization()
    {
        $student = Student::factory()->create();
        $organization = Organization::factory()->create();

        // Join organization
        $member = $student->joinOrganization($organization->id, 'member');
        
        $this->assertTrue($student->hasMembership($organization->id));

        // Leave organization
        $result = $student->leaveOrganization($organization->id);
        
        $this->assertTrue($result);
        $this->assertFalse($student->hasMembership($organization->id));
    }

    public function test_student_gets_total_organization_count()
    {
        $student = Student::factory()->create();
        $org1 = Organization::factory()->create();
        $org2 = Organization::factory()->create();
        
        $org1->addMember($student->id, 'member');
        $org2->addMember($student->id, 'leader');

        $this->assertEquals(2, $student->getTotalOrganizations());
    }
}