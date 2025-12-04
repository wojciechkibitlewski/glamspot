<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;


class BlogAdminController extends Controller
{
    public function index()
    {
        return view('sites.admin.blog.index');
    }

    public function create()
    {
        $categories = PostCategory::orderBy('category')->get();

        return view('sites.admin.blog.create', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'post_category_id' => ['required', 'integer', 'exists:post_categories,id'],
            'lead' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string'],
            'seo_keywords' => ['nullable', 'string'],
            'is_published' => ['boolean'],
        ]);

        // Get featured image from session (uploaded via Livewire)
        $featuredImage = session('blog_featured_image_pending');

        $post = Post::create([
            'title' => $validated['title'],
            'post_category_id' => $validated['post_category_id'],
            'lead' => $validated['lead'] ?? null,
            'description' => $validated['description'] ?? null,
            'seo_title' => $validated['seo_title'] ?? null,
            'seo_description' => $validated['seo_description'] ?? null,
            'seo_keywords' => $validated['seo_keywords'] ?? null,
            'is_published' => $request->boolean('is_published'),
            'published_at' => $request->boolean('is_published') ? now() : null,
            'featured_image' => $featuredImage,
        ]);

        // Clear session
        session()->forget('blog_featured_image_pending');

        return redirect()->route('admin.blog')
            ->with('message', 'Artykuł został dodany pomyślnie.');
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        $categories = PostCategory::orderBy('category')->get();

        return view('sites.admin.blog.edit', [
            'post' => $post,
            'categories' => $categories,
        ]);
    }
    
}
