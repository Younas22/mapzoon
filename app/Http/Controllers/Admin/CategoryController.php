<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\StoreCategoryRequest;
use App\Http\Requests\Admin\Category\UpdateCategoryRequest;
use App\Models\BlogPost;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', BlogPost::class);

        return view('admin.categories.index', [
            'categories' => $this->orderedCategories(),
            'parents' => Category::query()->whereNull('parent_id')->orderBy('name')->get(),
        ]);
    }

    public function data(Request $request): JsonResponse
    {
        $this->authorize('viewAny', BlogPost::class);

        $categories = $this->orderedCategories();

        return response()->json([
            'html' => view('admin.categories.partials.table', compact('categories'))->render(),
        ]);
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        Category::query()->create([
            ...$request->validated(),
            'slug' => Str::slug($request->validated('name')),
        ]);

        return response()->json(['message' => 'Category created successfully.'], 201);
    }

    public function edit(Category $category): JsonResponse
    {
        abort_unless(auth()->user()->hasPermission('blogs.edit'), 403);

        return response()->json([
            'category' => $category->only('id', 'name', 'description', 'parent_id'),
        ]);
    }

    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        $category->update([
            ...$request->validated(),
            'slug' => Str::slug($request->validated('name')),
        ]);

        return response()->json(['message' => 'Category updated successfully.']);
    }

    public function destroy(Category $category): JsonResponse
    {
        abort_unless(auth()->user()->hasPermission('blogs.delete'), 403);

        if ($category->children()->exists()) {
            return response()->json([
                'message' => 'This category has subcategories. Delete or move them first.',
            ], 422);
        }

        if ($category->posts()->exists()) {
            return response()->json([
                'message' => 'This category has blog posts assigned and cannot be deleted.',
            ], 422);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully.']);
    }

    protected function orderedCategories()
    {
        return Category::query()
            ->withCount('posts')
            ->whereNull('parent_id')
            ->with(['children' => fn ($query) => $query->withCount('posts')->orderBy('name')])
            ->orderBy('name')
            ->get();
    }
}
