<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\OrganizationPeriod;
use Illuminate\Http\Request;

class OrganizationPeriodController extends Controller
{
    public function index(Organization $organization)
    {
        $periods = $organization->periods()
                               ->withCount('members')
                               ->orderBy('start_date', 'desc')
                               ->paginate(15);

        return view('admin.periods.index', compact('organization', 'periods'));
    }

    public function create(Organization $organization)
    {
        return view('admin.periods.create', compact('organization'));
    }

    public function store(Request $request, Organization $organization)
    {
        $request->validate([
            'period_name' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
            'description' => 'nullable|string|max:1000'
        ]);

        $period = $organization->periods()->create($request->only([
            'period_name', 'start_date', 'end_date', 'is_active', 'description'
        ]));

        return redirect()
            ->route('admin.organizations.periods.index', $organization)
            ->with('success', 'Period created successfully!');
    }

    public function show(Organization $organization, OrganizationPeriod $period)
    {
        $period->load(['members.student', 'members.teacher']);
        
        $memberStats = [
            'total' => $period->members()->count(),
            'active' => $period->members()->where('status', 'active')->count(),
            'leadership' => $period->members()->leadership()->count()
        ];

        return view('admin.periods.show', compact('organization', 'period', 'memberStats'));
    }

    public function edit(Organization $organization, OrganizationPeriod $period)
    {
        return view('admin.periods.edit', compact('organization', 'period'));
    }

    public function update(Request $request, Organization $organization, OrganizationPeriod $period)
    {
        $request->validate([
            'period_name' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
            'description' => 'nullable|string|max:1000'
        ]);

        $period->update($request->only([
            'period_name', 'start_date', 'end_date', 'is_active', 'description'
        ]));

        return redirect()
            ->route('admin.organizations.periods.index', $organization)
            ->with('success', 'Period updated successfully!');
    }

    public function destroy(Organization $organization, OrganizationPeriod $period)
    {
        // Check if period has members
        if ($period->members()->exists()) {
            return back()->withErrors(['error' => 'Cannot delete period with existing members.']);
        }

        $period->delete();

        return redirect()
            ->route('admin.organizations.periods.index', $organization)
            ->with('success', 'Period deleted successfully!');
    }

    public function activate(Organization $organization, OrganizationPeriod $period)
    {
        $period->activate();

        return back()->with('success', 'Period activated successfully!');
    }

    public function updateLeadership(Request $request, Organization $organization, OrganizationPeriod $period)
    {
        $request->validate([
            'leadership' => 'required|array',
            'leadership.*.role' => 'required|string|max:100',
            'leadership.*.member_id' => 'required|exists:members,id'
        ]);

        $leadershipStructure = [];
        
        foreach ($request->leadership as $leadership) {
            $member = $period->members()->find($leadership['member_id']);
            
            if ($member) {
                $leadershipStructure[$leadership['role']] = [
                    'member_id' => $member->id,
                    'name' => $member->full_name,
                    'appointed_at' => now()->toDateString()
                ];
            }
        }

        $period->update(['leadership_structure' => $leadershipStructure]);

        return back()->with('success', 'Leadership structure updated successfully!');
    }
}
