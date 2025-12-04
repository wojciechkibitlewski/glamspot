<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PostCategoryAdminController extends Controller
{
    /**
     * Show all blog categories.
     */
    public function category(): View
    {
        return $this->index();
    }

    /**
     * List categories (alias for category()).
     */
    public function index(): View
    {
        $categories = PostCategory::withCount('posts')
            ->orderBy('category')
            ->paginate(15);

        return view('sites.admin.blog.categories.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show create form.
     */
    public function create(): View
    {
        return view('sites.admin.blog.categories.create');
    }

    /**
     * Store a new category.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateCategory($request);
        $data['slug'] = $this->prepareSlug($data);

        PostCategory::create($data);

        return redirect()
            ->route('admin.blog.categories.index')
            ->with('message', 'Kategoria została dodana.');
    }

    /**
     * Show edit form.
     */
    public function edit(PostCategory $postCategory): View
    {
        return view('sites.admin.blog.categories.edit', [
            'category' => $postCategory,
        ]);
    }

    /**
     * Update an existing category.
     */
    public function update(Request $request, PostCategory $postCategory): RedirectResponse
    {
        $data = $this->validateCategory($request, $postCategory->id);
        $data['slug'] = $this->prepareSlug($data);

        $postCategory->update($data);

        return redirect()
            ->route('admin.blog.categories.index')
            ->with('message', 'Kategoria została zaktualizowana.');
    }

    /**
     * Delete a category.
     */
    public function destroy(PostCategory $postCategory): RedirectResponse
    {
        if ($postCategory->posts()->exists()) {
            return back()->withErrors('Nie można usunąć kategorii, która ma przypisane artykuły.');
        }

        $postCategory->delete();

        return redirect()
            ->route('admin.blog.categories.index')
            ->with('message', 'Kategoria została usunięta.');
    }

    /**
     * Common validation rules.
     */
    protected function validateCategory(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'category' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('post_categories', 'slug')->ignore($ignoreId),
            ],
            'description' => ['nullable', 'string'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string'],
            'seo_keywords' => ['nullable', 'string'],
        ]);
    }

    /**
     * Normalize slug input.
     */
    protected function prepareSlug(array $data): ?string
    {
        return ! empty($data['slug']) ? Str::slug($data['slug']) : null;
    }
}
