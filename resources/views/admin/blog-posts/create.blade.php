<x-admin-layout title="Add Blog Post">
    @include('admin.blog-posts._form', [
        'post' => $post,
        'categories' => $categories,
        'tags' => $tags,
        'authors' => $authors,
        'selectedTagIds' => $selectedTagIds,
        'action' => route('admin.blog-posts.store'),
        'method' => 'POST',
        'submitLabel' => 'Create Post',
    ])
</x-admin-layout>
