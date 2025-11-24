<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::select('id', 'title', 'category', 'is_published', 'created_at')
                    ->latest()->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'excerpt' => 'required|max:500',
            'content' => 'required',
            'category' => 'required|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Max 2MB
        ]);

        $data = [
            'title' => $request->title,
            'excerpt' => $request->excerpt,
            'content' => strip_tags($request->content, '<p><br><strong><em><ul><ol><li><a><h1><h2><h3><h4><blockquote>'),
            'category' => $request->category,
            'is_published' => $request->status === 'published'
        ];

        // Handle image upload if present
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = \Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME));
            $extension = $image->getClientOriginalExtension();
            $safeName = $filename . '_' . time() . '.' . $extension;
            $path = $image->storeAs('posts', $safeName, 'public');
            $data['image'] = $path;
        }

        Post::create($data);
        return redirect()->route('admin.posts.index')->with('success', 'Post created successfully');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|max:255',
            'excerpt' => 'required|max:500',
            'content' => 'required',
            'category' => 'required|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Max 2MB
        ]);

        $data = [
            'title' => $request->title,
            'excerpt' => $request->excerpt,
            'content' => strip_tags($request->content, '<p><br><strong><em><ul><ol><li><a><h1><h2><h3><h4><blockquote>'),
            'category' => $request->category,
            'is_published' => $request->status === 'published'
        ];

        // Handle image upload if present
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($post->image && \Storage::disk('public')->exists($post->image)) {
                \Storage::disk('public')->delete($post->image);
            }
            
            $image = $request->file('image');
            $filename = \Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME));
            $extension = $image->getClientOriginalExtension();
            $safeName = $filename . '_' . time() . '.' . $extension;
            $path = $image->storeAs('posts', $safeName, 'public');
            $data['image'] = $path;
        }

        $post->update($data);
        return redirect()->route('admin.posts.index')->with('success', 'Post updated successfully');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Post deleted successfully');
    }
}