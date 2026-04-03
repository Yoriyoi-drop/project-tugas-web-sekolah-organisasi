<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Http\Requests\Admin\StoreActivityRequest;
use App\Http\Requests\Admin\UpdateActivityRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::select('id', 'title', 'slug', 'date', 'location', 'category', 'created_at')
                             ->latest()->paginate(10);
        return view('admin.activities.index', compact('activities'));
    }

    public function create()
    {
        return view('admin.activities.create');
    }

    public function store(StoreActivityRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $safeName = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('activities', $safeName, 'public');
            $data['image'] = $path;
        }

        $data['title'] = strip_tags($data['title']);
        $data['description'] = strip_tags($data['description'], '<p><b><i><u><ul><ol><li><br>');

        Activity::create($data);
        return redirect()->route('admin.activities.index')->with('success', 'Activity created successfully');
    }

    public function show(Activity $activity)
    {
        return view('admin.activities.show', compact('activity'));
    }

    public function edit(Activity $activity)
    {
        return view('admin.activities.edit', compact('activity'));
    }

    public function update(UpdateActivityRequest $request, Activity $activity)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($activity->image && Storage::disk('public')->exists($activity->image)) {
                Storage::disk('public')->delete($activity->image);
            }

            $image = $request->file('image');
            $safeName = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('activities', $safeName, 'public');
            $data['image'] = $path;
        }

        $data['title'] = strip_tags($data['title']);
        $data['description'] = strip_tags($data['description'], '<p><b><i><u><ul><ol><li><br>');

        $activity->update($data);
        return redirect()->route('admin.activities.index')->with('success', 'Activity updated successfully');
    }

    public function destroy(Activity $activity)
    {
        if ($activity->image && Storage::disk('public')->exists($activity->image)) {
            Storage::disk('public')->delete($activity->image);
        }
        
        $activity->delete();
        return redirect()->route('admin.activities.index')->with('success', 'Activity deleted successfully');
    }
}