<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Organization;
use App\Models\Activity;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard admin
     */
    public function index()
    {
        // Data statistik untuk dashboard admin
        $totalUsers = User::count();
        $totalPosts = Post::count();
        $totalOrganizations = Organization::count();
        $totalActivities = Activity::count();
        
        // Ambil beberapa data terbaru
        $latestUsers = User::orderBy('created_at', 'desc')->take(5)->get();
        $latestPosts = Post::orderBy('created_at', 'desc')->take(5)->get();
        
        return view('admin.dashboard.index', compact(
            'totalUsers', 
            'totalPosts', 
            'totalOrganizations', 
            'totalActivities',
            'latestUsers',
            'latestPosts'
        ));
    }
}