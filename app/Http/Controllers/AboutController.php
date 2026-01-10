<?php
namespace App\Http\Controllers;

class AboutController extends Controller
{
    public function index()
    {
        $data = \Illuminate\Support\Facades\Cache::remember('about_data', 3600, function () {
            return [
                'statistics' => \App\Models\Statistic::active()->ordered()->get(),
                'values' => \App\Models\Value::active()->ordered()->get(),
                'facilities' => \App\Models\Facility::active()->ordered()->get()
            ];
        });

        return view('pages.tentang', $data);
    }
}
