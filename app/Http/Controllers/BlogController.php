<?php
namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category');
        $search = $request->get('search');
        
        $featuredPost = Cache::remember('featured_post', 1800, function () {
            return Post::select('id', 'title', 'excerpt', 'icon', 'color', 'category', 'author', 'published_at', 'created_at')
                      ->published()->featured()->latest()->first();
        });
        
        $postsQuery = Post::select('id', 'title', 'excerpt', 'icon', 'color', 'category', 'published_at', 'created_at')
                         ->published()->where('is_featured', false);
        
        if ($category) {
            $postsQuery->where('category', $category);
        }
        
        if ($search) {
            $postsQuery->where(function($query) use ($search) {
                $query->where('title', 'LIKE', '%' . $search . '%')
                      ->orWhere('excerpt', 'LIKE', '%' . $search . '%')
                      ->orWhere('content', 'LIKE', '%' . $search . '%')
                      ->orWhere('category', 'LIKE', '%' . $search . '%');
            });
        }
        
        $posts = $postsQuery->latest()->paginate(6)->appends(request()->query());
        
        $recentPosts = Cache::remember('recent_posts', 900, function () {
            return Post::select('id', 'title', 'icon', 'published_at', 'created_at')
                      ->published()->latest()->take(4)->get();
        });
        
        // Get categories with post counts
        $categories = Post::select('category')
                         ->published()
                         ->groupBy('category')
                         ->selectRaw('category, count(*) as count')
                         ->get();
        
        $totalPosts = Post::published()->count();
        
        return view('pages.blog', compact('featuredPost', 'posts', 'recentPosts', 'categories', 'totalPosts', 'category', 'search'));
    }
}
