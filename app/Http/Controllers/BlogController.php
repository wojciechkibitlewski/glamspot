<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

use App\Models\Post;
use App\Models\PostCategory;

class BlogController extends Controller
{
    public function index()
    {
        $latestPost = Post::where('is_published', true)
            ->with(['category'])
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->first();

        $postsQuery = Post::where('is_published', true)
            ->with(['category', 'tags'])
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc');
          
        if ($latestPost) {
            $postsQuery->where('id', '!=', $latestPost->id);
        }

        $posts = $postsQuery->paginate(15);

        $categories = PostCategory::withCount(['posts' => function ($query) {
            $query->where('is_published', true);
        }])->get();

        return view('sites.blog.index', compact('posts', 'categories', 'latestPost'));

    }

    public function show(string $slug, string $code, string $postSlug): View
    {
        $post = Post::where('code', $code)
            ->where('is_published', true)
            ->firstOrFail();

        $relatedPosts = Post::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->where('post_category_id', $post->post_category_id)
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        return view('sites.blog.show', compact('post', 'relatedPosts'));
    }

    public function category(string $slug): View
    {
        $category = PostCategory::where('slug', $slug)->firstOrFail();

        $posts = Post::where('is_published', true)
            ->where('post_category_id', $category->id)
            ->with(['category', 'tags'])
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $categories = PostCategory::withCount(['posts' => function ($query) {
            $query->where('is_published', true);
        }])->get();

        return view('sites.blog.category', compact('category', 'posts', 'categories'));
    }
}
