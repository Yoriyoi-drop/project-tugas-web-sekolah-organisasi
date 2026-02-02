<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PerformanceMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'period_id',
        'date',
        'member_retention_rate',
        'member_acquisition_rate',
        'member_satisfaction_score',
        'total_activities_completed',
        'activity_completion_rate',
        'average_participation_rate',
        'activity_satisfaction_score',
        'budget_utilization',
        'cost_per_member',
        'roi_score',
        'discussion_engagement_rate',
        'announcement_read_rate',
        'notification_effectiveness',
        'overall_performance_score',
        'performance_grade',
        'growth_rate',
        'benchmark_score'
    ];

    protected $casts = [
        'date' => 'date',
        'member_retention_rate' => 'decimal:2',
        'member_acquisition_rate' => 'decimal:2',
        'member_satisfaction_score' => 'decimal:2',
        'activity_completion_rate' => 'decimal:2',
        'average_participation_rate' => 'decimal:2',
        'activity_satisfaction_score' => 'decimal:2',
        'budget_utilization' => 'decimal:2',
        'cost_per_member' => 'decimal:2',
        'roi_score' => 'decimal:2',
        'discussion_engagement_rate' => 'decimal:2',
        'announcement_read_rate' => 'decimal:2',
        'notification_effectiveness' => 'decimal:2',
        'overall_performance_score' => 'decimal:2',
        'growth_rate' => 'decimal:2',
        'benchmark_score' => 'decimal:2'
    ];

    protected $attributes = [
        'member_retention_rate' => 0,
        'member_acquisition_rate' => 0,
        'member_satisfaction_score' => 0,
        'total_activities_completed' => 0,
        'activity_completion_rate' => 0,
        'average_participation_rate' => 0,
        'activity_satisfaction_score' => 0,
        'budget_utilization' => 0,
        'cost_per_member' => 0,
        'roi_score' => 0,
        'discussion_engagement_rate' => 0,
        'announcement_read_rate' => 0,
        'notification_effectiveness' => 0,
        'overall_performance_score' => 0,
        'growth_rate' => 0,
        'benchmark_score' => 0
    ];

    // Relationships
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function period()
    {
        return $this->belongsTo(OrganizationPeriod::class, 'period_id');
    }

    // Scopes
    public function scopeForOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    public function scopeForPeriod($query, $periodId)
    {
        return $query->where('period_id', $periodId);
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('date', '>=', now()->subDays($days));
    }

    public function scopeByGrade($query, $grade)
    {
        return $query->where('performance_grade', $grade);
    }

    public function scopeHighPerforming($query)
    {
        return $query->whereIn('performance_grade', ['A', 'B']);
    }

    public function scopeLowPerforming($query)
    {
        return $query->whereIn('performance_grade', ['D', 'F']);
    }

    // Accessors
    public function getFormattedDateAttribute()
    {
        return $this->date->format('d M Y');
    }

    public function getGradeColorAttribute()
    {
        $colors = [
            'A' => 'success',
            'B' => 'info',
            'C' => 'warning',
            'D' => 'danger',
            'F' => 'dark'
        ];
        
        return $colors[$this->performance_grade] ?? 'secondary';
    }

    public function getPerformanceLevelAttribute()
    {
        $score = $this->overall_performance_score;
        
        if ($score >= 90) return 'Excellent';
        if ($score >= 80) return 'Good';
        if ($score >= 70) return 'Average';
        if ($score >= 60) return 'Below Average';
        return 'Poor';
    }

    public function getFormattedMetricsAttribute()
    {
        return [
            'member_retention_rate' => number_format($this->member_retention_rate, 2) . '%',
            'member_acquisition_rate' => number_format($this->member_acquisition_rate, 2) . '%',
            'member_satisfaction_score' => number_format($this->member_satisfaction_score, 2),
            'activity_completion_rate' => number_format($this->activity_completion_rate, 2) . '%',
            'average_participation_rate' => number_format($this->average_participation_rate, 2) . '%',
            'activity_satisfaction_score' => number_format($this->activity_satisfaction_score, 2),
            'budget_utilization' => number_format($this->budget_utilization, 2) . '%',
            'discussion_engagement_rate' => number_format($this->discussion_engagement_rate, 2) . '%',
            'announcement_read_rate' => number_format($this->announcement_read_rate, 2) . '%',
            'notification_effectiveness' => number_format($this->notification_effectiveness, 2) . '%',
            'overall_performance_score' => number_format($this->overall_performance_score, 2),
            'growth_rate' => number_format($this->growth_rate, 2) . '%',
            'benchmark_score' => number_format($this->benchmark_score, 2)
        ];
    }

    // Methods
    public function calculateGrade()
    {
        $score = $this->overall_performance_score;
        
        if ($score >= 90) $grade = 'A';
        elseif ($score >= 80) $grade = 'B';
        elseif ($score >= 70) $grade = 'C';
        elseif ($score >= 60) $grade = 'D';
        else $grade = 'F';
        
        $this->update(['performance_grade' => $grade]);
        
        return $grade;
    }

    public function calculateOverallScore()
    {
        // Weight different categories
        $weights = [
            'membership' => 0.3,
            'activity' => 0.3,
            'engagement' => 0.2,
            'financial' => 0.1,
            'growth' => 0.1
        ];
        
        $scores = [
            'membership' => $this->calculateMembershipScore(),
            'activity' => $this->calculateActivityScore(),
            'engagement' => $this->calculateEngagementScore(),
            'financial' => $this->calculateFinancialScore(),
            'growth' => $this->calculateGrowthScore()
        ];
        
        $overallScore = 0;
        foreach ($weights as $category => $weight) {
            $overallScore += $scores[$category] * $weight;
        }
        
        $this->update(['overall_performance_score' => $overallScore]);
        $this->calculateGrade();
        
        return $overallScore;
    }

    private function calculateMembershipScore()
    {
        $score = 0;
        
        // Retention rate (40 points)
        $score += min(40, ($this->member_retention_rate / 100) * 40);
        
        // Acquisition rate (30 points)
        $score += min(30, ($this->member_acquisition_rate / 20) * 30); // 20% = full points
        
        // Satisfaction (30 points)
        $score += min(30, ($this->member_satisfaction_score / 5) * 30); // 5/5 = full points
        
        return min(100, $score);
    }

    private function calculateActivityScore()
    {
        $score = 0;
        
        // Completion rate (40 points)
        $score += min(40, ($this->activity_completion_rate / 100) * 40);
        
        // Participation rate (30 points)
        $score += min(30, ($this->average_participation_rate / 80) * 30); // 80% = full points
        
        // Satisfaction (30 points)
        $score += min(30, ($this->activity_satisfaction_score / 5) * 30); // 5/5 = full points
        
        return min(100, $score);
    }

    private function calculateEngagementScore()
    {
        $score = 0;
        
        // Discussion engagement (35 points)
        $score += min(35, ($this->discussion_engagement_rate / 50) * 35); // 50% = full points
        
        // Announcement read rate (35 points)
        $score += min(35, ($this->announcement_read_rate / 90) * 35); // 90% = full points
        
        // Notification effectiveness (30 points)
        $score += min(30, ($this->notification_effectiveness / 80) * 30); // 80% = full points
        
        return min(100, $score);
    }

    private function calculateFinancialScore()
    {
        $score = 0;
        
        // Budget utilization (50 points)
        $score += min(50, ($this->budget_utilization / 100) * 50);
        
        // ROI score (50 points)
        $score += min(50, ($this->roi_score / 100) * 50);
        
        return min(100, $score);
    }

    private function calculateGrowthScore()
    {
        $score = 0;
        
        // Growth rate (60 points)
        $score += min(60, max(0, ($this->growth_rate + 10) * 3)); // Adjust for negative growth
        
        // Benchmark performance (40 points)
        $score += min(40, ($this->benchmark_score / 100) * 40);
        
        return min(100, $score);
    }

    public function compareWithBenchmark()
    {
        $benchmark = BenchmarkData::where('organization_type', $this->organization->type)
                                 ->where('benchmark_date', $this->date)
                                 ->first();
        
        if (!$benchmark) return null;
        
        return [
            'member_retention' => [
                'current' => $this->member_retention_rate,
                'benchmark' => $benchmark->benchmark_value,
                'percentile' => $this->getPercentile($this->member_retention_rate, $benchmark)
            ],
            'activity_completion' => [
                'current' => $this->activity_completion_rate,
                'benchmark' => $benchmark->benchmark_value,
                'percentile' => $this->getPercentile($this->activity_completion_rate, $benchmark)
            ]
        ];
    }

    private function getPercentile($value, $benchmark)
    {
        if ($value >= $benchmark->percentile_90) return 90;
        if ($value >= $benchmark->percentile_75) return 75;
        if ($value >= $benchmark->percentile_50) return 50;
        if ($value >= $benchmark->percentile_25) return 25;
        return 10;
    }

    // Static methods
    public static function generateMetrics($organizationId, $date = null)
    {
        $date = $date ?? now()->toDateString();
        $organization = Organization::find($organizationId);
        
        if (!$organization) return null;
        
        $metrics = static::firstOrCreate([
            'organization_id' => $organizationId,
            'date' => $date
        ]);
        
        // Calculate metrics from analytics and other data
        $data = [
            'member_retention_rate' => $metrics->calculateRetentionRate(),
            'member_acquisition_rate' => $metrics->calculateAcquisitionRate(),
            'activity_completion_rate' => $metrics->calculateActivityCompletionRate(),
            'average_participation_rate' => $metrics->calculateParticipationRate(),
            'discussion_engagement_rate' => $metrics->calculateDiscussionEngagement(),
            'announcement_read_rate' => $metrics->calculateAnnouncementReadRate(),
            'growth_rate' => $metrics->calculateGrowthRate()
        ];
        
        $metrics->update($data);
        $metrics->calculateOverallScore();
        
        return $metrics;
    }

    private function calculateRetentionRate()
    {
        // Implementation for calculating retention rate
        $previousMonth = now()->subMonth();
        $currentMembers = $this->organization->activeMembers()->count();
        $previousMembers = $this->organization->members()
                                            ->where('created_at', '<', $previousMonth)
                                            ->where('status', 'active')
                                            ->count();
        
        return $previousMembers > 0 ? ($currentMembers / $previousMembers) * 100 : 100;
    }

    private function calculateAcquisitionRate()
    {
        $totalMembers = $this->organization->members()->count();
        $newMembers = $this->organization->members()
                                         ->where('created_at', '>=', now()->subMonth())
                                         ->count();
        
        return $totalMembers > 0 ? ($newMembers / $totalMembers) * 100 : 0;
    }

    private function calculateActivityCompletionRate()
    {
        $totalActivities = $this->organization->activities()->count();
        $completedActivities = $this->organization->activities()
                                                 ->where('status', 'completed')
                                                 ->count();
        
        return $totalActivities > 0 ? ($completedActivities / $totalActivities) * 100 : 0;
    }

    private function calculateParticipationRate()
    {
        $totalMembers = $this->organization->activeMembers()->count();
        $totalParticipants = $this->organization->activities()
                                                ->where('status', 'completed')
                                                ->sum('registered_count');
        
        return $totalMembers > 0 ? ($totalParticipants / $totalMembers) * 100 : 0;
    }

    private function calculateDiscussionEngagement()
    {
        $totalDiscussions = $this->organization->discussions()->count();
        $totalReplies = $this->organization->discussions()->sum('reply_count');
        
        return $totalDiscussions > 0 ? ($totalReplies / $totalDiscussions) * 100 : 0;
    }

    private function calculateAnnouncementReadRate()
    {
        $totalNotifications = $this->organization->notifications()->count();
        $readNotifications = $this->organization->notifications()
                                                ->where('is_read', true)
                                                ->count();
        
        return $totalNotifications > 0 ? ($readNotifications / $totalNotifications) * 100 : 0;
    }

    private function calculateGrowthRate()
    {
        $currentPeriod = static::where('organization_id', $this->organization_id)
                               ->where('date', '<', $this->date)
                               ->orderBy('date', 'desc')
                               ->first();
        
        if (!$currentPeriod) return 0;
        
        $previousScore = $currentPeriod->overall_performance_score;
        $currentScore = $this->overall_performance_score;
        
        return $previousScore > 0 ? (($currentScore - $previousScore) / $previousScore) * 100 : 0;
    }
}
