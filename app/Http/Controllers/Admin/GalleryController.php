<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::with(['organization', 'uploader'])
            ->latest()
            ->paginate(12);
        return view('admin.galleries.index', compact('galleries'));
    }

    public function create()
    {
        $organizations = Organization::select('id', 'name')->get();
        return view('admin.galleries.create', compact('organizations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'organization_id' => 'nullable|exists:organizations,id',
            'is_public' => 'boolean',
            'tags' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('galleries', 'public');

            $validated['image_path'] = $path;
            $validated['thumbnail_path'] = $path; // simplified - same image
        }

        $validated['uploaded_by'] = auth()->id();
        $validated['is_public'] = $request->boolean('is_public', true);

        if ($request->filled('tags')) {
            $validated['tags'] = array_filter(array_map('trim', explode(',', $request->tags)));
        }

        $validated['title'] = strip_tags($validated['title']);
        if ($request->filled('description')) {
            $validated['description'] = strip_tags($validated['description']);
        }

        Gallery::create($validated);

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Galeri berhasil ditambahkan.');
    }

    public function show(Gallery $gallery)
    {
        $gallery->load(['organization', 'uploader']);
        return view('admin.galleries.show', compact('gallery'));
    }

    public function edit(Gallery $gallery)
    {
        $organizations = Organization::select('id', 'name')->get();
        return view('admin.galleries.edit', compact('gallery', 'organizations'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'organization_id' => 'nullable|exists:organizations,id',
            'is_public' => 'boolean',
            'tags' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
                Storage::disk('public')->delete($gallery->image_path);
            }
            if ($gallery->thumbnail_path && Storage::disk('public')->exists($gallery->thumbnail_path)) {
                Storage::disk('public')->delete($gallery->thumbnail_path);
            }

            $image = $request->file('image');
            $path = $image->store('galleries', 'public');

            $validated['image_path'] = $path;
            $validated['thumbnail_path'] = $path;
        }

        $validated['is_public'] = $request->boolean('is_public');

        if ($request->filled('tags')) {
            $validated['tags'] = array_filter(array_map('trim', explode(',', $request->tags)));
        } else {
            $validated['tags'] = null;
        }

        $validated['title'] = strip_tags($validated['title']);
        if ($request->filled('description')) {
            $validated['description'] = strip_tags($validated['description']);
        }

        $gallery->update($validated);

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Galeri berhasil diperbarui.');
    }

    public function destroy(Gallery $gallery)
    {
        // Delete associated files
        if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
            Storage::disk('public')->delete($gallery->image_path);
        }
        if ($gallery->thumbnail_path && Storage::disk('public')->exists($gallery->thumbnail_path)) {
            Storage::disk('public')->delete($gallery->thumbnail_path);
        }

        $gallery->delete();

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Galeri berhasil dihapus.');
    }
}
