<?php
namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Support\Facades\Cache;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Cache::remember('all_organizations', 3600, function () {
            return Organization::select('id', 'slug', 'name', 'type', 'description', 'icon', 'color', 'tagline', 'member_count')
                             ->where('is_active', true)
                             ->orderBy('order')
                             ->get();
        });
        
        return view('organisasi.index', compact('organizations'));
    }

    public function show(Organization $organization)
    {
        $organization->load([
            'activeMembers.student', 
            'activeMembers.teacher',
            'activePeriod',
            'periods' => function($query) {
                $query->orderBy('start_date', 'desc')->limit(3);
            }
        ]);

        $memberStats = $organization->getMemberCountByStatus();
        $leadershipMembers = $organization->getLeadershipMembers();
        
        return view('organisasi.show', compact('organization', 'memberStats', 'leadershipMembers'));
    }
}
