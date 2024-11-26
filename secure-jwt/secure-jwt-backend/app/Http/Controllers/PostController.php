<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    // 🚀 Optimized index method with caching
    public function index(Request $request)
    {
        // 🌟 Get the current page number or default to 1
        $page = $request->input('page', 1);

        // 🗃️ Cache paginated posts for 10 minutes per page
        $posts = Cache::remember("posts_page_{$page}", 600, function () use ($request) {
            return Post::with('user:id,name')
            ->select('id', 'title', 'content', 'user_id', 'created_at')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        });

        return response()->json($posts);
    }


    /**
     * Fetch user posts 📝 with CSRF protection 🛡️
     */
    public function fetchUserPosts(Request $request)
    {
        $user = auth()->user(); // 🎯 Automatically handles JWT via middleware

        // 🌟 Get paginated posts for the authenticated user
        $posts = Post::where('user_id', $user->id)
            ->select('id', 'title', 'content', 'created_at')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($posts);
    }
}
