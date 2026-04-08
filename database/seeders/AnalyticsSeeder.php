<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;
use App\Models\OrganizationAnalytics;
use App\Models\PerformanceMetric;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;

class AnalyticsSeeder extends Seeder
{
    public function run()
    {
        $organizations = Organization::all();
        $adminUser = User::where('email', 'admin@example.com')->first();

        foreach ($organizations as $org) {
            // Generate analytics data for the last 30 days
            $this->generateOrganizationAnalytics($org);
            
            // Generate performance metrics
            $this->generatePerformanceMetrics($org);
        }

        // Generate sample reports
        $this->generateSampleReports($adminUser);
    }

    private function generateOrganizationAnalytics($organization)
    {
        $baseDate = now()->subDays(30);
        
        for ($i = 0; $i < 30; $i++) {
            $date = $baseDate->copy()->addDays($i);
            
            // Generate realistic metrics with some variation
            $totalMembers = $organization->members()->count();
            $activeMembers = $organization->activeMembers()->count();
            
            // Add some growth over time
            $growthFactor = 1 + ($i * 0.02); // 2% growth per day
            $randomFactor = rand(80, 120) / 100; // Random variation
            
            $analytics = OrganizationAnalytics::create([
                'organization_id' => $organization->id,
                'date' => $date->toDateString(),
                'total_members' => $totalMembers,
                'active_members' => $activeMembers,
                'new_members' => rand(0, 3),
                'member_growth_rate' => min(20, ($i * 0.5) * $randomFactor),
                'total_activities' => rand(1, 4),
                'upcoming_activities' => rand(0, 2),
                'completed_activities' => rand(0, 2),
                'total_participants' => rand(10, 50),
                'attendance_rate' => min(95, 60 + ($i * 1.2) + rand(-10, 10)),
                'total_discussions' => rand(0, 3),
                'discussion_replies' => rand(0, 15),
                'total_announcements' => rand(0, 2),
                'total_notifications' => rand(5, 20),
                'read_notifications' => rand(3, 18),
            ]);
            
            // Calculate scores
            $analytics->calculateScores();
        }
    }

    private function generatePerformanceMetrics($organization)
    {
        $baseDate = now()->subDays(30);
        
        for ($i = 0; $i < 30; $i++) {
            $date = $baseDate->copy()->addDays($i);
            
            // Generate realistic performance metrics
            $retentionRate = min(95, 75 + rand(-10, 20));
            $acquisitionRate = min(25, rand(5, 20));
            $satisfactionScore = rand(35, 48) / 10;

            $performance = PerformanceMetric::create([
                'organization_id' => $organization->id,
                'date' => $date->toDateString(),
                'member_retention_rate' => $retentionRate,
                'member_acquisition_rate' => $acquisitionRate,
                'member_satisfaction_score' => $satisfactionScore,
                'total_activities_completed' => rand(0, 3),
                'activity_completion_rate' => min(100, 70 + rand(-20, 30)),
                'average_participation_rate' => min(90, 60 + rand(-15, 25)),
                'activity_satisfaction_score' => rand(32, 46) / 10,
                'budget_utilization' => min(100, rand(60, 95)),
                'cost_per_member' => rand(5000, 25000) / 100,
                'roi_score' => min(100, rand(70, 95)),
                'discussion_engagement_rate' => min(80, rand(20, 70)),
                'announcement_read_rate' => min(95, rand(70, 90)),
                'notification_effectiveness' => min(85, rand(60, 80)),
                'growth_rate' => rand(-5, 15),
                'benchmark_score' => min(100, rand(65, 90))
            ]);
            
            // Calculate overall score and grade
            $performance->calculateOverallScore();
        }
    }

    private function generateSampleReports($adminUser)
    {
        if (!$adminUser) {
            return; // Skip if admin user not found
        }
        
        $organizations = Organization::all();
        
        // Generate different types of reports
        $reportTypes = [
            [
                'type' => 'membership',
                'title' => 'Monthly Membership Report',
                'description' => 'Comprehensive membership statistics and trends'
            ],
            [
                'type' => 'activity',
                'title' => 'Activity Performance Report',
                'description' => 'Analysis of activity participation and completion rates'
            ],
            [
                'type' => 'engagement',
                'title' => 'Member Engagement Report',
                'description' => 'Discussion participation and communication metrics'
            ],
            [
                'type' => 'performance',
                'title' => 'Organization Performance Report',
                'description' => 'Overall performance metrics and KPI tracking'
            ]
        ];

        foreach ($organizations as $org) {
            foreach ($reportTypes as $reportData) {
                // Create 2-3 reports per organization per type
                for ($i = 0; $i < rand(2, 4); $i++) {
                    $report = Report::create([
                        'organization_id' => $org->id,
                        'created_by' => $adminUser->id,
                        'title' => $reportData['title'] . ' - ' . $org->name,
                        'description' => $reportData['description'],
                        'type' => $reportData['type'],
                        'format' => ['pdf', 'excel', 'csv'][rand(0, 2)],
                        'status' => 'completed',
                        'filters' => [
                            'start_date' => now()->subDays(30)->toDateString(),
                            'end_date' => now()->toDateString(),
                            'organization_id' => $org->id
                        ],
                        'file_path' => 'reports/sample_' . $reportData['type'] . '_' . $org->id . '_' . $i . '.pdf',
                        'file_name' => 'sample_' . $reportData['type'] . '_' . $org->name . '_' . $i . '.pdf',
                        'file_size' => rand(100000, 2000000), // 100KB - 2MB
                        'generated_at' => now()->subDays(rand(1, 30)),
                        'expires_at' => now()->addDays(30),
                        'download_count' => rand(0, 15)
                    ]);
                }
            }
        }

        // Generate some global reports (not organization-specific)
        for ($i = 0; $i < 5; $i++) {
            Report::create([
                'organization_id' => null,
                'created_by' => $adminUser->id,
                'title' => 'Global Analytics Report - ' . now()->subDays($i * 7)->format('M d'),
                'description' => 'System-wide analytics and performance metrics',
                'type' => 'performance',
                'format' => 'pdf',
                'status' => 'completed',
                'filters' => [
                    'start_date' => now()->subDays(60)->toDateString(),
                    'end_date' => now()->toDateString()
                ],
                'file_path' => 'reports/global_performance_' . $i . '.pdf',
                'file_name' => 'global_performance_report_' . $i . '.pdf',
                'file_size' => rand(500000, 3000000),
                'generated_at' => now()->subDays($i * 7),
                'expires_at' => now()->addDays(60),
                'download_count' => rand(5, 25)
            ]);
        }
    }
}
