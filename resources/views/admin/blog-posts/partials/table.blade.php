@php
    $sort = request()->query('sort', 'created_at');
    $dir = request()->query('dir', 'desc');
    $arrow = fn (string $field) => $sort === $field ? ($dir === 'asc' ? '↑' : '↓') : '';

    $statusColors = [
        'draft' => 'bg-slate-100 text-slate-600',
        'published' => 'bg-primary-50 text-primary-700',
        'scheduled' => 'bg-amber-50 text-amber-700',
    ];
@endphp

<div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white">
    <table class="w-full min-w-[860px] text-left text-sm">
        <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
            <tr>
                <th class="cursor-pointer select-none px-4 py-3" data-sort="title">Post {{ $arrow('title') }}</th>
                <th class="px-4 py-3">Category</th>
                <th class="px-4 py-3">Author</th>
                <th class="px-4 py-3">Status</th>
                <th class="cursor-pointer select-none px-4 py-3" data-sort="published_at">Published {{ $arrow('published_at') }}</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($posts as $post)
                <tr>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            @if ($post->featuredImageUrl())
                                <img src="{{ $post->featuredImageUrl() }}" alt="" class="h-10 w-14 flex-none rounded-lg object-cover">
                            @else
                                <span class="flex h-10 w-14 flex-none items-center justify-center rounded-lg bg-slate-100 text-xs text-slate-400">No image</span>
                            @endif
                            <div>
                                <span class="block font-medium text-ink">{{ $post->title }}</span>
                                @if ($post->is_featured)
                                    <span class="mt-0.5 inline-flex rounded-full bg-amber-50 px-2 py-0.5 text-xs font-medium text-amber-700">Homepage Featured</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-slate-500">{{ $post->category?->name ?? '—' }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $post->author?->name ?? '—' }}</td>
                    <td class="px-4 py-3">
                        <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $statusColors[$post->status] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ $post->statusLabel() }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-slate-500">{{ $post->published_at?->format('M d, Y') ?? '—' }}</td>
                    <td class="px-4 py-3 text-right">
                        <div class="inline-flex items-center gap-3">
                            <a href="{{ route('admin.blog-posts.preview', $post) }}" target="_blank" class="text-sm font-medium text-slate-600 hover:text-ink">Preview</a>
                            @can('update', $post)
                                <a href="{{ route('admin.blog-posts.edit', $post) }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">Edit</a>
                            @endcan
                            @can('delete', $post)
                                <button type="button" class="text-sm font-medium text-rose-600 hover:text-rose-700"
                                        @click="confirmDelete({{ $post->id }}, '{{ addslashes($post->title) }}')">
                                    Delete
                                </button>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-10 text-center text-slate-400">No blog posts found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($posts->hasPages())
        <div class="flex items-center justify-between border-t border-slate-200 px-4 py-3 text-sm text-slate-500">
            <span>Page {{ $posts->currentPage() }} of {{ $posts->lastPage() }} ({{ $posts->total() }} posts)</span>
            <div class="flex gap-2">
                <button type="button" data-page="{{ $posts->currentPage() - 1 }}" @if ($posts->onFirstPage()) disabled @endif
                        class="rounded-lg border border-slate-200 px-3 py-1.5 disabled:cursor-not-allowed disabled:opacity-40 hover:bg-slate-50">
                    Previous
                </button>
                <button type="button" data-page="{{ $posts->currentPage() + 1 }}" @if (! $posts->hasMorePages()) disabled @endif
                        class="rounded-lg border border-slate-200 px-3 py-1.5 disabled:cursor-not-allowed disabled:opacity-40 hover:bg-slate-50">
                    Next
                </button>
            </div>
        </div>
    @endif
</div>
