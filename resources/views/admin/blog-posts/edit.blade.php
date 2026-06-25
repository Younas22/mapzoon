<x-admin-layout title="Edit Blog Post">
    @include('admin.blog-posts._form', [
        'post' => $post,
        'categories' => $categories,
        'tags' => $tags,
        'authors' => $authors,
        'selectedTagIds' => $selectedTagIds,
        'action' => route('admin.blog-posts.update', $post),
        'method' => 'PUT',
        'submitLabel' => 'Save Changes',
    ])
</x-admin-layout>
