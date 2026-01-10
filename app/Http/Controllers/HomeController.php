<?php

namespace App\Http\Controllers;

use App\Models\{Post, Organization, Activity};
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $data = Cache::remember('home_data', 3600, function () {
            return [
                'statistics' => \App\Models\Statistic::select('value', 'description')
                                                     ->where('is_active', true)
                                                     ->orderBy('order')
                                                     ->get(),
                'organizations' => Organization::select('id', 'slug', 'name', 'type', 'description', 'icon', 'color', 'image', 'tags')
                                               ->active()
                                               ->ordered()
                                               ->get(),
                'latestPosts' => Post::select('id', 'slug', 'title', 'excerpt', 'icon', 'color', 'category', 'published_at', 'created_at')
                                     ->published()
                                     ->latest()
                                     ->take(3)
                                     ->get(),
                'upcomingActivities' => Activity::select('id', 'slug', 'title', 'description', 'date', 'location', 'category')
                                               ->upcoming()
                                               ->orderBy('date')
                                               ->take(3)
                                               ->get()
            ];
        });
        
        return view('pages.beranda', $data);
    }

    public function welcome()
    {
        return view('welcome');
    }
}
