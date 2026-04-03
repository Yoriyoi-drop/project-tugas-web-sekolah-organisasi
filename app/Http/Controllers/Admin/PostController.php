<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Http\Requests\Admin\StorePostRequest;
use App\Http\Requests\Admin\UpdatePostRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::select('id', 'title', 'slug', 'category', 'is_published', 'created_at')
                    ->latest()->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(StorePostRequest $request)
    {
        $data = [
            'title' => $request->title,
            'excerpt' => $request->excerpt,
            // Basic XSS protection: allow only safe tags
            'content' => strip_tags($request->content, '<p><b><i><u><a><ul><ol><li><h1><h2><h3><h4><h5><h6><br><img><blockquote><div><span>'), 
            'category' => $request->category,
            'author' => Auth::user()->name ?? 'Admin',
            'is_published' => in_array($request->status, ['published', 'on', 'true', 1]),
        ];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // Secure upload: Use UUID to prevent collision and guessing
            $safeName = Str::uuid() . '.' . $image->getClientOriginalExtension();
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

    public function update(UpdatePostRequest $request, Post $post)
    {
        $data = [
            'title' => $request->title,
            'excerpt' => $request->excerpt,
             // Basic XSS protection
            'content' => strip_tags($request->content, '<p><b><i><u><a><ul><ol><li><h1><h2><h3><h4><h5><h6><br><img><blockquote><div><span>'),
            'category' => $request->category,
            'is_published' => in_array($request->status, ['published', 'on', 'true', 1]),
        ];

        if ($request->hasFile('image')) {
            if ($post->image && Storage::disk('public')->exists($post->image)) {
                Storage::disk('public')->delete($post->image);
            }
            
            $image = $request->file('image');
            $safeName = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('posts', $safeName, 'public');
            $data['image'] = $path;
        }

        $post->update($data);
        return redirect()->route('admin.posts.index')->with('success', 'Post updated successfully');
    }

    public function destroy(Post $post)
    {
        if ($post->image && Storage::disk('public')->exists($post->image)) {
            Storage::disk('public')->delete($post->image);
        }
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Post deleted successfully');
    }
}