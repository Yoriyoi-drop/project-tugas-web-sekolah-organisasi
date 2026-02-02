<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;
use App\Models\OrganizationPeriod;
use App\Models\Member;
use App\Models\Student;
use App\Models\Teacher;

class OrganizationPeriodSeeder extends Seeder
{
    public function run()
    {
        $organizations = Organization::all();
        $currentPeriod = date('Y') . '/' . (date('Y') + 1);
        $previousPeriod = (date('Y') - 1) . '/' . date('Y');

        foreach ($organizations as $org) {
            // Create current period
            $currentPeriodRecord = OrganizationPeriod::create([
                'organization_id' => $org->id,
                'period_name' => $currentPeriod,
                'start_date' => now()->startOfYear(),
                'end_date' => now()->endOfYear(),
                'is_active' => true,
                'description' => "Periode kepengurusan {$currentPeriod} untuk {$org->name}",
                'member_count' => 0
            ]);

            // Create previous period
            OrganizationPeriod::create([
                'organization_id' => $org->id,
                'period_name' => $previousPeriod,
                'start_date' => now()->subYear()->startOfYear(),
                'end_date' => now()->subYear()->endOfYear(),
                'is_active' => false,
                'description' => "Periode kepengurusan {$previousPeriod} untuk {$org->name}",
                'member_count' => 0
            ]);

            // Add some sample members for current period
            $this->addSampleMembers($org, $currentPeriodRecord);
        }
    }

    private function addSampleMembers($organization, $period)
    {
        // Get sample students and teachers
        $students = Student::inRandomOrder()->limit(5)->get();
        $teachers = Teacher::inRandomOrder()->limit(2)->get();

        // Add student members with leadership roles
        $leadershipRoles = ['leader', 'vice_leader', 'secretary', 'treasurer'];
        $studentCount = 0;

        foreach ($students as $student) {
            $role = $studentCount < count($leadershipRoles) ? $leadershipRoles[$studentCount] : 'member';
            $position = $this->getPositionForRole($role, $organization->name);

            Member::create([
                'organization_id' => $organization->id,
                'student_id' => $student->id,
                'role' => $role,
                'position' => $position,
                'period' => $period->period_name,
                'join_date' => now()->subMonths(rand(1, 6)),
                'status' => 'active',
                'skills' => $this->getSampleSkills(),
                'achievements' => $this->getSampleAchievements()
            ]);

            $studentCount++;
        }

        // Add teacher advisors
        foreach ($teachers as $teacher) {
            Member::create([
                'organization_id' => $organization->id,
                'teacher_id' => $teacher->id,
                'role' => 'advisor',
                'position' => 'Pembina',
                'period' => $period->period_name,
                'join_date' => now()->subMonths(rand(1, 6)),
                'status' => 'active'
            ]);
        }

        // Update member count
        $period->updateMemberCount();
        $organization->updateMemberCount();
    }

    private function getPositionForRole($role, $orgName)
    {
        $positions = [
            'leader' => $this->getLeaderTitle($orgName),
            'vice_leader' => 'Wakil ' . $this->getLeaderTitle($orgName),
            'secretary' => 'Sekretaris',
            'treasurer' => 'Bendahara'
        ];

        return $positions[$role] ?? null;
    }

    private function getLeaderTitle($orgName)
    {
        if (str_contains($orgName, 'OSIS')) return 'Ketua OSIS';
        if (str_contains($orgName, 'IPNU')) return 'Ketua IPNU';
        if (str_contains($orgName, 'IPPNU')) return 'Ketua IPPNU';
        if (str_contains($orgName, 'Pagar Nusa')) return 'Ketua Pagar Nusa';
        if (str_contains($orgName, 'Banser')) return 'Komandan Banser';
        if (str_contains($orgName, 'Qurra')) return 'Ketua Jam\'iyyah Qurra';
        
        return 'Ketua Organisasi';
    }

    private function getSampleSkills()
    {
        $allSkills = [
            'Public Speaking', 'Leadership', 'Event Planning', 'Writing', 'Design',
            'Communication', 'Problem Solving', 'Team Work', 'Time Management',
            'Critical Thinking', 'Creativity', 'Adaptability'
        ];

        return array_rand(array_flip($allSkills), rand(2, 4));
    }

    private function getSampleAchievements()
    {
        $achievements = [
            ['title' => 'Best Organization 2024', 'date' => '2024-12-15'],
            ['title' => 'Leadership Excellence Award', 'date' => '2024-10-20'],
            ['title' => 'Community Service Champion', 'date' => '2024-08-10']
        ];

        return array_rand(array_flip($achievements), rand(0, 2));
    }
}
