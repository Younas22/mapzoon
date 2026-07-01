<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $posts = BlogPost::query()
            ->published()
            ->with(['category', 'author'])
            ->when($request->category, fn ($q, $slug) => $q->whereHas('category', fn ($c) => $c->where('slug', $slug)))
            ->latest('published_at')
            ->paginate(9);

        $categories = Category::query()
            ->whereHas('posts', fn ($q) => $q->published())
            ->orderBy('name')
            ->get();

        $featured = BlogPost::query()->published()->featured()->latest('published_at')->first();

        return view('pages.blog.index', [
            'posts' => $posts,
            'categories' => $categories,
            'featured' => $featured,
            'title' => 'Blog — MAPZOON',
            'description' => 'Read MAPZOON\'s latest articles on Google Maps SEO, local business growth, website development, and POS billing tips.',
        ]);
    }

    public function show(string $slug): View
    {
        $post = BlogPost::query()
            ->published()
            ->with(['category', 'tags', 'author', 'seo', 'faqs'])
            ->where('slug', $slug)
            ->firstOrFail();

        $related = BlogPost::query()
            ->published()
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->latest('published_at')
            ->limit(4)
            ->get();

        $previous = BlogPost::query()->published()->where('published_at', '<', $post->published_at)->latest('published_at')->first();
        $next = BlogPost::query()->published()->where('published_at', '>', $post->published_at)->oldest('published_at')->first();

        $seo = $post->seo;

        return view('frontend.blog-details', [
            'post' => $post,
            'related' => $related,
            'previous' => $previous,
            'next' => $next,
            'recentPosts' => BlogPost::query()->published()->where('id', '!=', $post->id)->latest('published_at')->limit(3)->get(),
            'categories' => Category::query()->whereHas('posts', fn ($query) => $query->published())->orderBy('name')->get(),
            'title' => ($seo?->meta_title ?: $post->title).' — MAPZOON Blog',
            'description' => $seo?->meta_description ?: $post->excerpt,
            'keywords' => $seo?->focus_keyword,
            'canonical' => $seo?->canonical_url ?: url()->current(),
            'ogTitle' => $seo?->og_title ?: $post->title,
            'ogDescription' => $seo?->og_description ?: $post->excerpt,
            'ogImage' => $post->featuredImageUrl(),
            'ogType' => 'article',
            'twitterCard' => $seo?->twitter_card ?: 'summary_large_image',
            'twitterTitle' => $seo?->twitter_title ?: ($seo?->og_title ?: $post->title),
            'twitterDescription' => $seo?->twitter_description ?: ($seo?->og_description ?: $post->excerpt),
            'twitterImage' => $post->featuredImageUrl(),
        ]);
    }
}
