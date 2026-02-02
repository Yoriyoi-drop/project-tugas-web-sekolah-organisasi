<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Organization;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_organization_can_add_member()
    {
        $organization = Organization::factory()->create();
        $student = \App\Models\Student::factory()->create();
        
        $member = $organization->addMember($student->id, 'member');
        
        $this->assertInstanceOf(Member::class, $member);
        $this->assertEquals($organization->id, $member->organization_id);
        $this->assertEquals($student->id, $member->student_id);
        $this->assertEquals('member', $member->role);
    }

    public function test_organization_cannot_add_duplicate_member()
    {
        $organization = Organization::factory()->create();
        $student = \App\Models\Student::factory()->create();

        $organization->addMember($student->id, 'member');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Student is already a member of this organization');

        $organization->addMember($student->id, 'member');
    }

    public function test_get_recent_activity_returns_correct_count()
    {
        $organization = Organization::factory()->create();

        // Create test data
        $user = User::factory()->create();

        $organization->discussions()->create([
            'title' => 'Test Discussion',
            'content' => 'Test content',
            'author_id' => $user->id,
            'status' => 'active'
        ]);

        $organization->activities()->create([
            'title' => 'Test Activity',
            'description' => 'Test description',
            'date' => now()->addDays(7),
            'start_datetime' => now()->addDays(7),
            'end_datetime' => now()->addDays(8),
            'status' => 'upcoming',
            'created_by' => $user->id
        ]);

        $organization->announcements()->create([
            'title' => 'Test Announcement',
            'content' => 'Test content',
            'priority' => 'normal',
            'status' => 'published',
            'author_id' => $user->id
        ]);

        $recentActivity = $organization->getRecentActivity();

        $this->assertLessThanOrEqual(5, $recentActivity->count());
        $this->assertGreaterThan(0, $recentActivity->count());
    }

    public function test_get_upcoming_events_returns_only_future_events()
    {
        $organization = Organization::factory()->create();
        $user = \App\Models\User::factory()->create();

        // Create past and future activities
        $organization->activities()->create([
            'title' => 'Past Activity',
            'description' => 'Past activity description',
            'date' => now()->subDays(7),
            'start_datetime' => now()->subDays(7),
            'end_datetime' => now()->subDays(6),
            'status' => 'completed',
            'created_by' => $user->id
        ]);

        $organization->activities()->create([
            'title' => 'Future Activity',
            'description' => 'Future activity description',
            'date' => now()->addDays(7),
            'start_datetime' => now()->addDays(7),
            'end_datetime' => now()->addDays(8),
            'status' => 'upcoming',
            'created_by' => $user->id
        ]);

        $upcomingEvents = $organization->getUpcomingEvents();

        $this->assertEquals(1, $upcomingEvents->count());
        $this->assertEquals('Future Activity', $upcomingEvents->first()->title);
    }

    public function test_get_member_count_by_status()
    {
        $organization = Organization::factory()->create();
        $student1 = \App\Models\Student::factory()->create();
        $student2 = \App\Models\Student::factory()->create();
        
        $organization->addMember($student1->id, 'member');
        $organization->addMember($student2->id, 'leader');
        
        $member2 = $organization->members()->where('student_id', $student2->id)->first();
        $member2->update(['status' => 'inactive']);
        
        $stats = $organization->getMemberCountByStatus();
        
        $this->assertEquals(1, $stats['active']);
        $this->assertEquals(1, $stats['inactive']);
        $this->assertEquals(2, $stats['total']);
    }
}
