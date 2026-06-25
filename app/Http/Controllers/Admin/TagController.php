<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Tag\StoreTagRequest;
use App\Http\Requests\Admin\Tag\UpdateTagRequest;
use App\Models\BlogPost;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TagController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', BlogPost::class);

        return view('admin.tags.index', [
            'tags' => $this->paginatedTags($request),
        ]);
    }

    public function data(Request $request): JsonResponse
    {
        $this->authorize('viewAny', BlogPost::class);

        $tags = $this->paginatedTags($request);

        return response()->json([
            'html' => view('admin.tags.partials.table', compact('tags'))->render(),
        ]);
    }

    public function store(StoreTagRequest $request): JsonResponse
    {
        Tag::query()->create([
            'name' => $request->validated('name'),
            'slug' => Str::slug($request->validated('name')),
        ]);

        return response()->json(['message' => 'Tag created successfully.'], 201);
    }

    public function edit(Tag $tag): JsonResponse
    {
        abort_unless(auth()->user()->hasPermission('blogs.edit'), 403);

        return response()->json([
            'tag' => $tag->only('id', 'name'),
        ]);
    }

    public function update(UpdateTagRequest $request, Tag $tag): JsonResponse
    {
        $tag->update([
            'name' => $request->validated('name'),
            'slug' => Str::slug($request->validated('name')),
        ]);

        return response()->json(['message' => 'Tag updated successfully.']);
    }

    public function destroy(Tag $tag): JsonResponse
    {
        abort_unless(auth()->user()->hasPermission('blogs.delete'), 403);

        $tag->delete();

        return response()->json(['message' => 'Tag deleted successfully.']);
    }

    protected function paginatedTags(Request $request)
    {
        return Tag::query()
            ->withCount('posts')
            ->when($request->filled('q'), fn ($query) => $query->where('name', 'like', '%'.$request->query('q').'%'))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();
    }
}
