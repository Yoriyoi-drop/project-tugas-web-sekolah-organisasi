<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function index(Request $request)
    {
        $query = Facility::where('status', 'active');
        
        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        $facilities = $query->orderBy('category')->orderBy('name')->paginate(12);
        $categories = Facility::where('status', 'active')
                             ->distinct()
                             ->pluck('category')
                             ->sort();
        
        return view('pages.fasilitas', compact('facilities', 'categories'));
    }
    
    public function show(Facility $facility)
    {
        if ($facility->status !== 'active') {
            abort(404);
        }
        
        $relatedFacilities = Facility::where('status', 'active')
                                   ->where('category', $facility->category)
                                   ->where('id', '!=', $facility->id)
                                   ->limit(3)
                                   ->get();
        
        return view('pages.fasilitas-detail', compact('facility', 'relatedFacilities'));
    }
}