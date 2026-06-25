<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class BlogController extends Controller
{
    public function show(string $slug): View
    {
        $posts = config('blog.posts');
        $index = collect($posts)->search(fn (array $post) => $post['slug'] === $slug);

        abort_if($index === false, 404);

        $post = $posts[$index];

        return view('frontend.blog-details', [
            'post' => $post,
            'related' => collect($posts)->reject(fn (array $p) => $p['slug'] === $slug)->values()->all(),
            'previous' => $posts[$index - 1] ?? null,
            'next' => $posts[$index + 1] ?? null,
            'title' => $post['title'].' — MAPZOON Blog',
            'description' => $post['excerpt'],
        ]);
    }
}
