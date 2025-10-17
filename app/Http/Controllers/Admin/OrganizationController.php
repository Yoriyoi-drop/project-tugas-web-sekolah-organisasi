<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::select('id', 'name', 'type', 'tagline', 'icon', 'color', 'tags', 'is_active', 'order', 'created_at')
                                   ->orderBy('order')
                                   ->orderBy('name')
                                   ->paginate(15);
        return view('admin.organizations.index', compact('organizations'));
    }

    public function create()
    {
        return view('admin.organizations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'type' => 'required|max:255',
            'description' => 'required',
            'icon' => 'required|max:255'
        ]);

        // Process programs
        $programs = [];
        if ($request->programs) {
            $programs = array_filter(array_map('trim', explode("\n", $request->programs)));
        }

        // Process leadership
        $leadership = [];
        if ($request->leadership_names && $request->leadership_positions) {
            foreach ($request->leadership_names as $index => $name) {
                if (!empty($name) && !empty($request->leadership_positions[$index])) {
                    $leadership[] = [
                        'name' => trim($name),
                        'position' => trim($request->leadership_positions[$index])
                    ];
                }
            }
        }

        // Process tags
        $tags = [];
        if ($request->tags) {
            $tags = array_filter(array_map('trim', explode(',', $request->tags)));
        }

        Organization::create([
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->description,
            'icon' => $request->icon,
            'color' => $request->color ?? 'primary',
            'tagline' => $request->tagline,
            'email' => $request->email,
            'phone' => $request->phone,
            'location' => $request->location,
            'tags' => $tags,
            'programs' => $programs,
            'leadership' => $leadership,
            'order' => $request->order ?? 0,
            'is_active' => $request->is_active ?? true
        ]);
        return redirect()->route('admin.organizations.index')->with('success', 'Organization created successfully');
    }

    public function edit(Organization $organization)
    {
        return view('admin.organizations.edit', compact('organization'));
    }

    public function update(Request $request, Organization $organization)
    {
        $request->validate([
            'name' => 'required|max:255',
            'type' => 'required|max:255',
            'description' => 'required',
            'icon' => 'required|max:255'
        ]);

        // Process programs
        $programs = [];
        if ($request->programs) {
            $programs = array_filter(array_map('trim', explode("\n", $request->programs)));
        }

        // Process leadership
        $leadership = [];
        if ($request->leadership_names && $request->leadership_positions) {
            foreach ($request->leadership_names as $index => $name) {
                if (!empty($name) && !empty($request->leadership_positions[$index])) {
                    $leadership[] = [
                        'name' => trim($name),
                        'position' => trim($request->leadership_positions[$index])
                    ];
                }
            }
        }

        // Process tags
        $tags = [];
        if ($request->tags) {
            $tags = array_filter(array_map('trim', explode(',', $request->tags)));
        }

        $organization->update([
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->description,
            'icon' => $request->icon,
            'color' => $request->color ?? 'primary',
            'tagline' => $request->tagline,
            'email' => $request->email,
            'phone' => $request->phone,
            'location' => $request->location,
            'tags' => $tags,
            'programs' => $programs,
            'leadership' => $leadership,
            'order' => $request->order ?? 0,
            'is_active' => $request->is_active ?? true
        ]);
        return redirect()->route('admin.organizations.index')->with('success', 'Organization updated successfully');
    }

    public function destroy(Organization $organization)
    {
        $organization->delete();
        return redirect()->route('admin.organizations.index')->with('success', 'Organization deleted successfully');
    }
}