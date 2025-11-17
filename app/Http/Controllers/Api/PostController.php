<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $posts = Post::with('category')
                     ->published()
                     ->when($request->category, function($query) use ($request) {
                         $query->where('category', $request->category);
                     })
                     ->when($request->search, function($query) use ($request) {
                         $query->where('title', 'LIKE', '%' . $request->search . '%')
                               ->orWhere('excerpt', 'LIKE', '%' . $request->search . '%')
                               ->orWhere('content', 'LIKE', '%' . $request->search . '%');
                     })
                     ->latest()
                     ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }

    public function show($id): JsonResponse
    {
        $post = Post::with('category')->find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $post
        ]);
    }
}
