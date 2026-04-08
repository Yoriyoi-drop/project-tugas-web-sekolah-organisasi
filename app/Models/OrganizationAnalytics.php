<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrganizationAnalytics extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'date',
        'total_members',
        'active_members',
        'new_members',
        'member_growth_rate',
        'total_activities',
        'upcoming_activities',
        'completed_activities',
        'total_participants',
        'attendance_rate',
        'total_discussions',
        'discussion_replies',
        'total_announcements',
        'total_notifications',
        'read_notifications',
        'activity_score',
        'engagement_score',
        'overall_score'
    ];

    protected $casts = [
        'date' => 'date',
        'member_growth_rate' => 'decimal:2',
        'attendance_rate' => 'decimal:2',
        'activity_score' => 'decimal:2',
        'engagement_score' => 'decimal:2',
        'overall_score' => 'decimal:2'
    ];

    protected $attributes = [
        'total_members' => 0,
        'active_members' => 0,
        'new_members' => 0,
        'member_growth_rate' => 0,
        'total_activities' => 0,
        'upcoming_activities' => 0,
        'completed_activities' => 0,
        'total_participants' => 0,
        'attendance_rate' => 0,
        'total_discussions' => 0,
        'discussion_replies' => 0,
        'total_announcements' => 0,
        'total_notifications' => 0,
        'read_notifications' => 0,
        'activity_score' => 0,
        'engagement_score' => 0,
        'overall_score' => 0
    ];

    // Relationships
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    // Scopes
    public function scopeForOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('date', '>=', now()->subDays($days));
    }

    public function scopeByMonth($query, $year, $month)
    {
        return $query->whereYear('date', $year)->whereMonth('date', $month);
    }

    public function scopeByYear($query, $year)
    {
        return $query->whereYear('date', $year);
    }

    // Accessors
    public function getFormattedDateAttribute()
    {
        return $this->date->format('d M Y');
    }

    public function getMemberGrowthRateFormattedAttribute()
    {
        return number_format((float)$this->member_growth_rate, 2) . '%';
    }

    public function getAttendanceRateFormattedAttribute()
    {
        return number_format((float)$this->attendance_rate, 2) . '%';
    }

    public function getScoresFormattedAttribute()
    {
        return [
            'activity' => number_format((float)$this->activity_score, 1),
            'engagement' => number_format((float)$this->engagement_score, 1),
            'overall' => number_format((float)$this->overall_score, 1)
        ];
    }

    public function getGradeAttribute()
    {
        $score = $this->overall_score;
        
        if ($score >= 90) return 'A';
        if ($score >= 80) return 'B';
        if ($score >= 70) return 'C';
        if ($score >= 60) return 'D';
        return 'F';
    }

    public function getGradeColorAttribute()
    {
        $grade = $this->grade;
        
        $colors = [
            'A' => 'success',
            'B' => 'info',
            'C' => 'warning',
            'D' => 'danger',
            'F' => 'dark'
        ];
        
        return $colors[$grade] ?? 'secondary';
    }

    // Methods
    public function calculateScores()
    {
        // Activity Score (40% weight)
        $activityScore = $this->calculateActivityScore();
        
        // Engagement Score (40% weight)
        $engagementScore = $this->calculateEngagementScore();
        
        // Membership Score (20% weight)
        $membershipScore = $this->calculateMembershipScore();
        
        // Overall Score (weighted average)
        $overallScore = ($activityScore * 0.4) + ($engagementScore * 0.4) + ($membershipScore * 0.2);
        
        $this->update([
            'activity_score' => $activityScore,
            'engagement_score' => $engagementScore,
            'overall_score' => $overallScore
        ]);
        
        return [
            'activity' => $activityScore,
            'engagement' => $engagementScore,
            'membership' => $membershipScore,
            'overall' => $overallScore
        ];
    }

    private function calculateActivityScore()
    {
        $score = 0;
        
        // Activity completion (30 points)
        if ($this->total_activities > 0) {
            $completionRate = ($this->completed_activities / $this->total_activities) * 100;
            $score += min(30, ($completionRate / 100) * 30);
        }
        
        // Attendance rate (30 points)
        $score += min(30, ($this->attendance_rate / 100) * 30);
        
        // Participation (20 points)
        if ($this->active_members > 0) {
            $participationRate = ($this->total_participants / $this->active_members) * 100;
            $score += min(20, ($participationRate / 100) * 20);
        }
        
        // Activity variety (20 points)
        if ($this->total_activities >= 5) $score += 20;
        elseif ($this->total_activities >= 3) $score += 15;
        elseif ($this->total_activities >= 1) $score += 10;
        
        return min(100, $score);
    }

    private function calculateEngagementScore()
    {
        $score = 0;
        
        // Discussion engagement (30 points)
        if ($this->total_discussions > 0) {
            $replyRate = ($this->discussion_replies / $this->total_discussions);
            $score += min(30, ($replyRate / 5) * 30); // 5 replies per discussion = full points
        }
        
        // Announcement effectiveness (30 points)
        if ($this->total_notifications > 0) {
            $readRate = ($this->read_notifications / $this->total_notifications) * 100;
            $score += min(30, ($readRate / 100) * 30);
        }
        
        // Content creation (20 points)
        $contentScore = min(20, ($this->total_discussions * 2) + ($this->total_announcements * 3));
        $score += $contentScore;
        
        // Member engagement (20 points)
        if ($this->active_members > 0) {
            $engagementPerMember = ($this->discussion_replies + $this->read_notifications) / $this->active_members;
            $score += min(20, ($engagementPerMember / 10) * 20); // 10 engagements per member = full points
        }
        
        return min(100, $score);
    }

    private function calculateMembershipScore()
    {
        $score = 0;
        
        // Member growth (40 points)
        $score += min(40, max(0, $this->member_growth_rate * 2)); // 20% growth = full points
        
        // Member retention (30 points)
        if ($this->total_members > 0) {
            $retentionRate = ($this->active_members / $this->total_members) * 100;
            $score += min(30, ($retentionRate / 100) * 30);
        }
        
        // Member acquisition (30 points)
        if ($this->total_members > 0) {
            $acquisitionRate = ($this->new_members / $this->total_members) * 100;
            $score += min(30, ($acquisitionRate / 20) * 30); // 20% new members = full points
        }
        
        return min(100, $score);
    }

    // Static methods
    public static function generateAnalytics($organizationId, $date = null)
    {
        $date = $date ?? now()->toDateString();
        $organization = Organization::find($organizationId);
        
        if (!$organization) return null;
        
        $analytics = static::firstOrCreate([
            'organization_id' => $organizationId,
            'date' => $date
        ]);
        
        // Calculate metrics
        $data = [
            'total_members' => $organization->members()->count(),
            'active_members' => $organization->activeMembers()->count(),
            'new_members' => $organization->members()->whereDate('created_at', $date)->count(),
            'total_activities' => $organization->activities()->whereDate('created_at', $date)->count(),
            'upcoming_activities' => $organization->upcomingActivities()->count(),
            'completed_activities' => $organization->activities()->where('status', 'completed')->whereDate('end_datetime', $date)->count(),
            'total_participants' => $organization->activities()->whereDate('start_datetime', $date)->sum('registered_count'),
            'total_discussions' => $organization->discussions()->whereDate('created_at', $date)->count(),
            'discussion_replies' => $organization->discussions()->whereDate('created_at', $date)->sum('reply_count'),
            'total_announcements' => $organization->announcements()->whereDate('created_at', $date)->count(),
            'total_notifications' => $organization->notifications()->whereDate('created_at', $date)->count(),
            'read_notifications' => $organization->notifications()->whereDate('created_at', $date)->where('is_read', true)->count(),
        ];
        
        // Calculate rates
        if ($data['total_members'] > 0) {
            $previousDate = now()->subDay()->toDateString();
            $previousMembers = $organization->members()->whereDate('created_at', '<', $date)->count();
            $data['member_growth_rate'] = $previousMembers > 0 ? 
                (($data['total_members'] - $previousMembers) / $previousMembers) * 100 : 0;
            
            $data['attendance_rate'] = $data['total_participants'] > 0 ? 
                ($data['total_participants'] / $data['active_members']) * 100 : 0;
        }
        
        $analytics->update($data);
        $analytics->calculateScores();
        
        return $analytics;
    }

    public static function getTrends($organizationId, $days = 30)
    {
        $analytics = static::forOrganization($organizationId)
                          ->recent($days)
                          ->orderBy('date')
                          ->get();
        
        return [
            'member_growth' => $analytics->pluck('total_members'),
            'activity_score' => $analytics->pluck('activity_score'),
            'engagement_score' => $analytics->pluck('engagement_score'),
            'overall_score' => $analytics->pluck('overall_score'),
            'dates' => $analytics->pluck('formatted_date')
        ];
    }

    public static function getComparison($organizationId, $period1, $period2)
    {
        $analytics1 = static::forOrganization($organizationId)
                           ->dateRange($period1['start'], $period1['end'])
                           ->get();
        
        $analytics2 = static::forOrganization($organizationId)
                           ->dateRange($period2['start'], $period2['end'])
                           ->get();
        
        return [
            'period1' => [
                'avg_activity_score' => $analytics1->avg('activity_score'),
                'avg_engagement_score' => $analytics1->avg('engagement_score'),
                'avg_overall_score' => $analytics1->avg('overall_score'),
                'total_new_members' => $analytics1->sum('new_members'),
                'total_activities' => $analytics1->sum('total_activities')
            ],
            'period2' => [
                'avg_activity_score' => $analytics2->avg('activity_score'),
                'avg_engagement_score' => $analytics2->avg('engagement_score'),
                'avg_overall_score' => $analytics2->avg('overall_score'),
                'total_new_members' => $analytics2->sum('new_members'),
                'total_activities' => $analytics2->sum('total_activities')
            ]
        ];
    }
}
