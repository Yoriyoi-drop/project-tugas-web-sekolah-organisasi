<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Post, Organization, Activity, User, Statistic};

class DashboardController extends Controller
{

    public function index()
    {
        $stats = [
            'posts' => Post::count(),
            'organizations' => Organization::count(),
            'activities' => Activity::count(),
            'students' => \App\Models\Student::count() ?? 0,
            'published_posts' => Post::where('is_published', true)->count(),
            'active_organizations' => Organization::where('is_active', true)->count(),
            'upcoming_activities' => Activity::where('date', '>=', now())->count(),
        ];

        $recentPosts = Post::select('id', 'title', 'created_at')
                          ->latest()->take(5)->get();
        $recentActivities = Activity::select('id', 'title', 'created_at')
                                   ->latest()->take(5)->get();

        return view('admin.dashboard.index', compact('stats', 'recentPosts', 'recentActivities'));
    }
}
