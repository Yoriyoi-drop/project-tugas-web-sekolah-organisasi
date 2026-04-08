<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\OrganizationAnalytics;
use App\Models\PerformanceMetric;
use App\Models\Report;
use App\Models\Activity;
use App\Models\Member;
use App\Models\OrganizationDiscussion;
use App\Models\OrganizationAnnouncement;
use App\Models\OrganizationActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $organizations = Organization::with(['activeMembers', 'activities', 'discussions', 'announcements'])->get();
        
        // Overall statistics
        $stats = [
            'total_organizations' => $organizations->count(),
            'total_members' => Member::where('status', 'active')->count(),
            'total_activities' => Activity::count(),
            'total_discussions' => OrganizationDiscussion::count(),
            'total_announcements' => OrganizationAnnouncement::count(),
            'active_organizations' => $organizations->where('is_active', true)->count(),
        ];
        
        // Top performing organizations
        $topOrganizations = $organizations->sortByDesc(function ($org) {
            return $org->activeMembers()->count() + $org->activities()->count();
        })->take(5);
        
        // Recent activity
        $recentActivity = $this->getRecentActivity();
        
        // Growth trends (last 30 days)
        $growthTrends = $this->getGrowthTrends();
        
        return view('admin.analytics.index', compact(
            'stats',
            'organizations',
            'topOrganizations',
            'recentActivity',
            'growthTrends'
        ));
    }
    
    public function organization(Organization $organization)
    {
        // Get organization analytics for the last 30 days
        $analytics = OrganizationAnalytics::forOrganization($organization->id)
                                         ->recent(30)
                                         ->orderBy('date')
                                         ->get();
        
        // Performance metrics
        $performance = PerformanceMetric::forOrganization($organization->id)
                                       ->recent(30)
                                       ->orderBy('date')
                                       ->get();
        
        // Current statistics
        $currentStats = [
            'total_members' => $organization->members()->count(),
            'active_members' => $organization->activeMembers()->count(),
            'total_activities' => $organization->activities()->count(),
            'upcoming_activities' => $organization->upcomingActivities()->count(),
            'completed_activities' => $organization->activities()->where('status', 'completed')->count(),
            'total_discussions' => $organization->discussions()->count(),
            'total_announcements' => $organization->announcements()->count(),
        ];
        
        // Engagement metrics
        $engagement = [
            'discussion_replies' => $organization->discussions()->sum('reply_count'),
            'total_views' => $organization->discussions()->sum('views') + $organization->activities()->sum('view_count'),
            'notification_reads' => $organization->notifications()->where('is_read', true)->count(),
            'total_notifications' => $organization->notifications()->count(),
        ];
        
        // Activity breakdown
        $activityBreakdown = $this->getActivityBreakdown($organization);
        
        // Member trends
        $memberTrends = $this->getMemberTrends($organization);
        
        return view('admin.analytics.organization', compact(
            'organization',
            'analytics',
            'performance',
            'currentStats',
            'engagement',
            'activityBreakdown',
            'memberTrends'
        ));
    }
    
    public function reports()
    {
        $reports = Report::with(['organization', 'creator'])
                        ->orderBy('created_at', 'desc')
                        ->paginate(20);
        
        $stats = Report::getStatistics();
        
        return view('admin.analytics.reports', compact('reports', 'stats'));
    }
    
    public function generateReport(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => 'nullable|exists:organizations,id',
            'type' => 'required|in:membership,activity,engagement,performance,custom',
            'format' => 'required|in:pdf,excel,csv',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);
        
        $filters = [
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'organization_id' => $validated['organization_id']
        ];
        
        $report = Report::createReport([
            'organization_id' => $validated['organization_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'format' => $validated['format'],
            'filters' => $filters
        ], auth()->id());
        
        return redirect()->route('admin.analytics.reports')
                        ->with('success', 'Report is being generated and will be available soon.');
    }
    
    public function downloadReport(Report $report)
    {
        try {
            return $report->download();
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Report is not available for download: ' . $e->getMessage());
        }
    }
    
    public function deleteReport(Report $report)
    {
        $report->delete();
        
        return redirect()->route('admin.analytics.reports')
                        ->with('success', 'Report deleted successfully.');
    }
    
    public function performance()
    {
        // Get all performance metrics
        $metrics = PerformanceMetric::with(['organization', 'period'])
                                   ->recent(30)
                                   ->orderBy('overall_performance_score', 'desc')
                                   ->paginate(20);
        
        // Performance distribution
        $gradeDistribution = PerformanceMetric::recent(30)
                                            ->selectRaw('performance_grade, COUNT(*) as count')
                                            ->groupBy('performance_grade')
                                            ->pluck('count', 'performance_grade');
        
        // Top performers
        $topPerformers = PerformanceMetric::highPerforming()
                                         ->with('organization')
                                         ->orderBy('overall_performance_score', 'desc')
                                         ->take(10)
                                         ->get();
        
        // Low performers
        $lowPerformers = PerformanceMetric::lowPerforming()
                                         ->with('organization')
                                         ->orderBy('overall_performance_score', 'asc')
                                         ->take(10)
                                         ->get();
        
        return view('admin.analytics.performance', compact(
            'metrics',
            'gradeDistribution',
            'topPerformers',
            'lowPerformers'
        ));
    }
    
    public function compare()
    {
        $organizations = Organization::all();
        
        return view('admin.analytics.compare', compact('organizations'));
    }
    
    public function compareResults(Request $request)
    {
        $validated = $request->validate([
            'organizations' => 'required|array|min:2|max:5',
            'organizations.*' => 'exists:organizations,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);
        
        $organizationIds = $validated['organizations'];
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        
        $comparisonData = [];
        
        foreach ($organizationIds as $orgId) {
            $organization = Organization::find($orgId);
            
            // Get analytics for the period
            $analytics = OrganizationAnalytics::forOrganization($orgId)
                                           ->dateRange($startDate, $endDate)
                                           ->get();
            
            // Get performance metrics
            $performance = PerformanceMetric::forOrganization($orgId)
                                          ->dateRange($startDate, $endDate)
                                          ->get();
            
            $comparisonData[$orgId] = [
                'organization' => $organization,
                'analytics' => $analytics,
                'performance' => $performance,
                'summary' => [
                    'avg_activity_score' => $analytics->avg('activity_score'),
                    'avg_engagement_score' => $analytics->avg('engagement_score'),
                    'avg_overall_score' => $analytics->avg('overall_score'),
                    'total_new_members' => $analytics->sum('new_members'),
                    'total_activities' => $analytics->sum('total_activities'),
                    'avg_performance_score' => $performance->avg('overall_performance_score')
                ]
            ];
        }
        
        return view('admin.analytics.compare-results', compact('comparisonData'));
    }
    
    private function getRecentActivity()
    {
        return collect()
            ->merge(OrganizationDiscussion::with('organization', 'author')->latest()->take(5)->get())
            ->merge(OrganizationActivity::with('organization', 'creator')->latest()->take(5)->get())
            ->merge(OrganizationAnnouncement::with('organization', 'author')->latest()->take(5)->get())
            ->sortByDesc('created_at')
            ->take(10);
    }
    
    private function getGrowthTrends()
    {
        $days = 30;
        $trends = [];
        
        for ($i = $days; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $trends[] = [
                'date' => $date->format('M d'),
                'members' => Member::whereDate('created_at', $date)->count(),
                'activities' => Activity::whereDate('created_at', $date)->count(),
                'discussions' => OrganizationDiscussion::whereDate('created_at', $date)->count(),
                'announcements' => OrganizationAnnouncement::whereDate('created_at', $date)->count()
            ];
        }
        
        return $trends;
    }
    
    private function getActivityBreakdown(Organization $organization)
    {
        return $organization->activities()
                          ->selectRaw('type, COUNT(*) as count, SUM(registered_count) as total_participants')
                          ->groupBy('type')
                          ->get()
                          ->map(function ($item) {
                              return [
                                  'type' => $item->type,
                                  'formatted_type' => $this->formatActivityType($item->type),
                                  'count' => $item->count,
                                  'total_participants' => $item->total_participants
                              ];
                          });
    }
    
    private function getMemberTrends(Organization $organization)
    {
        $days = 30;
        $trends = [];
        
        for ($i = $days; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $trends[] = [
                'date' => $date->format('M d'),
                'new_members' => $organization->members()->whereDate('created_at', $date)->count(),
                'total_members' => $organization->members()->where('created_at', '<=', $date)->count()
            ];
        }
        
        return $trends;
    }
    
    private function formatActivityType($type)
    {
        $types = [
            'meeting' => 'Rapat',
            'event' => 'Acara',
            'training' => 'Pelatihan',
            'competition' => 'Kompetisi',
            'social' => 'Sosial',
            'religious' => 'Keagamaan',
            'other' => 'Lainnya'
        ];
        
        return $types[$type] ?? 'Acara';
    }
}
