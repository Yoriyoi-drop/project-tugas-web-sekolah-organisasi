<?php
namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Support\Facades\Cache;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::select('id', 'title', 'description', 'date', 'location', 'created_at')
                             ->latest()
                             ->paginate(12);
        
        return view('pages.kegiatan', compact('activities'));
    }
}
