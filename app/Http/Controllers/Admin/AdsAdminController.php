<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdsAdminController extends Controller
{
    public function index()
    {
        return view('sites.admin.ads.index');
    }

    /**
     * List all categories with their subcategories.
     */
    public function category(): View
    {
        $categories = Category::with(['subcategories' => function ($query) {
            $query->orderBy('name');
        }])->orderBy('name')->paginate(15);

        return view('sites.admin.ads.categories.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * List all subcategories.
     */
    public function subcategories(): View
    {
        $subcategories = Subcategory::with('category')
            ->join('categories', 'categories.id', '=', 'subcategories.category_id')
            ->orderBy('categories.name')
            ->orderBy('subcategories.name')
            ->select('subcategories.*')
            ->paginate(20);

        return view('sites.admin.ads.subcategories.index', [
            'subcategories' => $subcategories,
        ]);
    }

    public function createCategory(): View
    {
        return view('sites.admin.ads.categories.create');
    }

    public function storeCategory(Request $request): RedirectResponse
    {
        $data = $this->validateCategory($request);
        $data['slug'] = $this->prepareSlug($data);

        Category::create($data);

        return redirect()
            ->route('admin.ads.categories.index')
            ->with('message', 'Kategoria została dodana.');
    }

    public function editCategory(Category $category): View
    {
        return view('sites.admin.ads.categories.edit', [
            'category' => $category,
        ]);
    }

    public function updateCategory(Request $request, Category $category): RedirectResponse
    {
        $data = $this->validateCategory($request, $category->id);
        $data['slug'] = $this->prepareSlug($data);

        $category->update($data);

        return redirect()
            ->route('admin.ads.categories.index')
            ->with('message', 'Kategoria została zaktualizowana.');
    }

    public function destroyCategory(Category $category): RedirectResponse
    {
        if ($category->subcategories()->exists()) {
            return back()->withErrors('Nie można usunąć kategorii, która ma podkategorie.');
        }

        $category->delete();

        return redirect()
            ->route('admin.ads.categories.index')
            ->with('message', 'Kategoria została usunięta.');
    }

    public function createSubcategory(Request $request): View
    {
        $selectedCategoryId = (int) $request->input('category_id');

        return view('sites.admin.ads.subcategories.create', [
            'categories' => Category::orderBy('name')->get(),
            'selectedCategoryId' => $selectedCategoryId,
        ]);
    }

    public function storeSubcategory(Request $request): RedirectResponse
    {
        $data = $this->validateSubcategory($request);
        $data['slug'] = $this->prepareSlug($data);

        Subcategory::create($data);

        return redirect()
            ->route('admin.ads.subcategories.index')
            ->with('message', 'Podkategoria została dodana.');
    }

    public function editSubcategory(Subcategory $subcategory): View
    {
        return view('sites.admin.ads.subcategories.edit', [
            'subcategory' => $subcategory,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function updateSubcategory(Request $request, Subcategory $subcategory): RedirectResponse
    {
        $data = $this->validateSubcategory($request, $subcategory->id);
        $data['slug'] = $this->prepareSlug($data);

        $subcategory->update($data);

        return redirect()
            ->route('admin.ads.subcategories.index')
            ->with('message', 'Podkategoria została zaktualizowana.');
    }

    public function destroySubcategory(Subcategory $subcategory): RedirectResponse
    {
        $subcategory->delete();

        return redirect()
            ->route('admin.ads.subcategories.index')
            ->with('message', 'Podkategoria została usunięta.');
    }

    protected function validateCategory(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('categories', 'slug')->ignore($ignoreId),
            ],
        ]);
    }

    protected function validateSubcategory(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('subcategories', 'slug')->ignore($ignoreId),
            ],
        ]);
    }

    protected function prepareSlug(array $data): ?string
    {
        return !empty($data['slug']) ? Str::slug($data['slug']) : null;
    }
}
