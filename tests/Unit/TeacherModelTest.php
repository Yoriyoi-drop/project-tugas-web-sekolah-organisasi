<?php

namespace Tests\Unit;

use App\Models\Teacher;
use App\Models\Organization;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_has_organizations_relationship()
    {
        $teacher = Teacher::factory()->create();
        $organization = Organization::factory()->create();
        
        $teacher->organizations()->attach($organization->id, ['role' => 'advisor']);

        $this->assertCount(1, $teacher->organizations);
        $this->assertInstanceOf(Organization::class, $teacher->organizations->first());
    }

    public function test_teacher_generates_nip_automatically()
    {
        $teacher = Teacher::factory()->create([
            'name' => 'John Smith',
            'email' => 'john@example.com',
            'subject' => 'Mathematics',
            'qualification' => 'M.Sc'
        ]);

        $this->assertNotNull($teacher->nip);
        $this->assertStringStartsWith(date('Y'), $teacher->nip);
    }

    public function test_teacher_can_get_name_with_nip()
    {
        $teacher = Teacher::factory()->create([
            'name' => 'Jane Smith',
            'nip' => '198012342008012001'
        ]);

        $expectedName = $teacher->name . ' (' . $teacher->nip . ')';
        $this->assertEquals($expectedName, $teacher->name . ' (' . $teacher->nip . ')');
    }

    public function test_teacher_can_be_advisor_of_organization()
    {
        $teacher = Teacher::factory()->create();
        $organization = Organization::factory()->create();

        $this->assertFalse($teacher->organizations()->where('organization_id', $organization->id)->exists());

        $teacher->organizations()->attach($organization->id, ['role' => 'advisor']);

        $this->assertTrue($teacher->organizations()->where('organization_id', $organization->id)->exists());
    }

    public function test_teacher_gets_organizations()
    {
        $teacher = Teacher::factory()->create();
        $organization = Organization::factory()->create();
        
        $teacher->organizations()->attach($organization->id, ['role' => 'advisor']);

        $organizations = $teacher->organizations;

        $this->assertCount(1, $organizations);
        $this->assertEquals($organization->id, $organizations->first()->id);
    }

    public function test_teacher_generate_nip_method()
    {
        $nip = Teacher::generateNip();

        $this->assertNotNull($nip);
        $this->assertStringStartsWith(date('Y'), $nip);
    }
}