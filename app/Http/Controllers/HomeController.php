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
                'organizations' => Organization::select('id', 'name', 'type', 'icon', 'color', 'description', 'tags')
                                               ->where('is_active', true)
                                               ->orderBy('order')
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
