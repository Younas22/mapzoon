<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogPost\StoreBlogPostRequest;
use App\Http\Requests\Admin\BlogPost\UpdateBlogPostRequest;
use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogPostController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', BlogPost::class);

        return view('admin.blog-posts.index', [
            'posts' => $this->filteredPosts($request),
            'categories' => $this->hierarchicalCategories(),
        ]);
    }

    public function data(Request $request): JsonResponse
    {
        $this->authorize('viewAny', BlogPost::class);

        $posts = $this->filteredPosts($request);

        return response()->json([
            'html' => view('admin.blog-posts.partials.table', compact('posts'))->render(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', BlogPost::class);

        return view('admin.blog-posts.create', [
            'post' => new BlogPost(['status' => 'draft']),
            'categories' => $this->hierarchicalCategories(),
            'tags' => Tag::query()->orderBy('name')->get(),
            'authors' => User::query()->orderBy('name')->get(),
            'selectedTagIds' => [],
        ]);
    }

    public function store(StoreBlogPostRequest $request): RedirectResponse
    {
        $post = new BlogPost($this->postAttributes($request));

        if ($request->hasFile('featured_image')) {
            $post->featured_image = $this->storePublicImage($request->file('featured_image'), 'blog');
        }

        $post->save();

        $post->tags()->sync($request->validated('tags', []));
        $post->seo()->create($this->seoAttributes($request));
        $post->faqs()->createMany($this->cleanFaqs($request->validated('faqs', [])));

        return redirect()->route('admin.blog-posts.edit', $post)->with('success', 'Blog post created successfully.');
    }

    public function edit(BlogPost $blog_post): View
    {
        $this->authorize('update', $blog_post);

        return view('admin.blog-posts.edit', [
            'post' => $blog_post->load(['seo', 'faqs']),
            'categories' => $this->hierarchicalCategories(),
            'tags' => Tag::query()->orderBy('name')->get(),
            'authors' => User::query()->orderBy('name')->get(),
            'selectedTagIds' => $blog_post->tags()->pluck('tags.id')->all(),
        ]);
    }

    public function update(UpdateBlogPostRequest $request, BlogPost $blog_post): RedirectResponse
    {
        $blog_post->fill($this->postAttributes($request));

        if ($request->hasFile('featured_image')) {
            $this->deletePublicImage($blog_post->featured_image);

            $blog_post->featured_image = $this->storePublicImage($request->file('featured_image'), 'blog');
        }

        $blog_post->save();

        $blog_post->tags()->sync($request->validated('tags', []));
        $blog_post->seo()->updateOrCreate(['blog_post_id' => $blog_post->id], $this->seoAttributes($request));

        $blog_post->faqs()->delete();
        $blog_post->faqs()->createMany($this->cleanFaqs($request->validated('faqs', [])));

        return redirect()->route('admin.blog-posts.edit', $blog_post)->with('success', 'Blog post updated successfully.');
    }

    public function destroy(BlogPost $blog_post): JsonResponse
    {
        $this->authorize('delete', $blog_post);

        $this->deletePublicImage($blog_post->featured_image);

        $blog_post->delete();

        return response()->json(['message' => 'Blog post deleted successfully.']);
    }

    /**
     * Uploads an image dropped into the content editor and returns its public URL.
     */
    public function uploadImage(Request $request): JsonResponse
    {
        abort_unless(
            $request->user()->hasPermission('blogs.create') || $request->user()->hasPermission('blogs.edit'),
            403
        );

        $request->validate([
            'image' => ['required', 'image', 'max:4096'],
        ]);

        $path = $this->storePublicImage($request->file('image'), 'blog-content');

        return response()->json(['url' => asset($path)]);
    }

    public function preview(BlogPost $blog_post): View
    {
        $this->authorize('view', $blog_post);

        $blog_post->load(['category', 'tags', 'author', 'seo', 'faqs']);

        return view('frontend.blog-details', [
            'post' => $blog_post,
            'related' => BlogPost::query()->published()->where('id', '!=', $blog_post->id)->latest('published_at')->limit(4)->get(),
            'previous' => null,
            'next' => null,
            'recentPosts' => BlogPost::query()->published()->where('id', '!=', $blog_post->id)->latest('published_at')->limit(3)->get(),
            'categories' => Category::query()->whereHas('posts', fn ($query) => $query->published())->orderBy('name')->get(),
            'title' => $blog_post->title.' — MAPZOON Blog (Preview)',
            'description' => $blog_post->excerpt,
        ]);
    }

    protected function postAttributes(StoreBlogPostRequest|UpdateBlogPostRequest $request): array
    {
        $status = $request->validated('status');
        $publishedAt = $request->validated('published_at');

        if ($status === 'published' && ! $publishedAt) {
            $publishedAt = now();
        } elseif ($status === 'draft') {
            $publishedAt = null;
        }

        return [
            'title' => $request->validated('title'),
            'slug' => $request->validated('slug'),
            'category_id' => $request->validated('category_id'),
            'author_id' => $request->validated('author_id') ?: Auth::id(),
            'excerpt' => $request->validated('excerpt'),
            'content' => $request->validated('content'),
            'status' => $status,
            'published_at' => $publishedAt,
            'is_featured' => $request->boolean('is_featured'),
        ];
    }

    protected function seoAttributes(StoreBlogPostRequest|UpdateBlogPostRequest $request): array
    {
        $seo = $request->validated('seo', []) ?? [];

        return [
            'meta_title' => $seo['meta_title'] ?? null,
            'meta_description' => $seo['meta_description'] ?? null,
            'focus_keyword' => $seo['focus_keyword'] ?? null,
            'canonical_url' => $seo['canonical_url'] ?? null,
            'og_title' => $seo['og_title'] ?? null,
            'og_description' => $seo['og_description'] ?? null,
            'twitter_card' => $seo['twitter_card'] ?? 'summary_large_image',
            'twitter_title' => $seo['twitter_title'] ?? null,
            'twitter_description' => $seo['twitter_description'] ?? null,
            'custom_schema' => $seo['custom_schema'] ?? null,
        ];
    }

    protected function cleanFaqs(array $faqs): array
    {
        $order = 0;

        return collect($faqs)
            ->filter(fn ($faq) => trim($faq['question'] ?? '') !== '' && trim($faq['answer'] ?? '') !== '')
            ->map(function ($faq) use (&$order) {
                return [
                    'question' => $faq['question'],
                    'answer' => $faq['answer'],
                    'sort_order' => $order++,
                ];
            })
            ->values()
            ->all();
    }

    /**
     * Moves an uploaded image into public/uploads/{folder} and returns its relative path.
     */
    protected function storePublicImage(UploadedFile $file, string $folder): string
    {
        $directory = "uploads/{$folder}";

        if (! is_dir(public_path($directory))) {
            mkdir(public_path($directory), 0755, true);
        }

        $filename = Str::random(40).'.'.$file->getClientOriginalExtension();

        $file->move(public_path($directory), $filename);

        return "{$directory}/{$filename}";
    }

    protected function deletePublicImage(?string $path): void
    {
        if ($path && file_exists(public_path($path))) {
            unlink(public_path($path));
        }
    }

    protected function filteredPosts(Request $request)
    {
        $sort = in_array($request->query('sort'), ['title', 'created_at', 'published_at']) ? $request->query('sort') : 'created_at';
        $dir = $request->query('dir') === 'asc' ? 'asc' : 'desc';

        return BlogPost::query()
            ->with(['category', 'author'])
            ->when($request->filled('q'), fn ($query) => $query->where('title', 'like', '%'.$request->query('q').'%'))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->query('status')))
            ->when($request->filled('category'), fn ($query) => $query->where('category_id', $request->query('category')))
            ->orderBy($sort, $dir)
            ->paginate(10)
            ->withQueryString();
    }

    protected function hierarchicalCategories()
    {
        return Category::query()
            ->whereNull('parent_id')
            ->with(['children' => fn ($query) => $query->orderBy('name')])
            ->orderBy('name')
            ->get();
    }
}
