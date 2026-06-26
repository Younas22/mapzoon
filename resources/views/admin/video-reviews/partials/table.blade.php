@php
    $sort = request()->query('sort', 'display_order');
    $dir = request()->query('dir', 'asc');
    $arrow = fn (string $field) => $sort === $field ? ($dir === 'asc' ? '↑' : '↓') : '';

    $statusColors = [
        'active' => 'bg-primary-50 text-primary-700',
        'inactive' => 'bg-slate-100 text-slate-600',
    ];
@endphp

<div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white">
    <table class="w-full min-w-[820px] text-left text-sm">
        <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
            <tr>
                <th class="cursor-pointer select-none px-4 py-3" data-sort="display_order">Order {{ $arrow('display_order') }}</th>
                <th class="cursor-pointer select-none px-4 py-3" data-sort="client_name">Client {{ $arrow('client_name') }}</th>
                <th class="px-4 py-3">Company</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Homepage</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($videoReviews as $review)
                <tr>
                    <td class="px-4 py-3 text-slate-500">{{ $review->display_order }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            @if ($review->thumbnailUrl())
                                <img src="{{ $review->thumbnailUrl() }}" alt="{{ $review->client_name }}" class="h-9 w-16 flex-none rounded-md object-cover bg-slate-100">
                            @else
                                <span class="flex h-9 w-9 flex-none items-center justify-center rounded-full bg-primary-100 text-xs font-bold text-primary-700">{{ $review->initials() }}</span>
                            @endif
                            <div>
                                <span class="block font-medium text-ink">{{ $review->client_name }}</span>
                                <span class="block text-xs text-slate-500">{{ $review->tagline ?: '—' }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-slate-500">{{ $review->company_name ?: '—' }}</td>
                    <td class="px-4 py-3">
                        <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $statusColors[$review->status] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ $review->statusLabel() }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @if ($review->is_visible_on_homepage)
                            <span class="rounded-full bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700">Visible</span>
                        @else
                            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">Hidden</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="inline-flex items-center gap-3">
                            <a href="{{ $review->youtube_url }}" target="_blank" rel="noopener noreferrer" class="text-sm font-medium text-slate-500 hover:text-ink">Watch</a>
                            @can('update', $review)
                                <button type="button" class="text-sm font-medium text-primary-600 hover:text-primary-700" @click="openEdit({{ $review->id }})">Edit</button>
                            @endcan
                            @can('delete', $review)
                                <button type="button" class="text-sm font-medium text-rose-600 hover:text-rose-700"
                                        @click="confirmDelete({{ $review->id }}, '{{ addslashes($review->client_name) }}')">
                                    Delete
                                </button>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-10 text-center text-slate-400">No video reviews found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($videoReviews->hasPages())
        <div class="flex items-center justify-between border-t border-slate-200 px-4 py-3 text-sm text-slate-500">
            <span>Page {{ $videoReviews->currentPage() }} of {{ $videoReviews->lastPage() }} ({{ $videoReviews->total() }} reviews)</span>
            <div class="flex gap-2">
                <button type="button" data-page="{{ $videoReviews->currentPage() - 1 }}" @if ($videoReviews->onFirstPage()) disabled @endif
                        class="rounded-lg border border-slate-200 px-3 py-1.5 disabled:cursor-not-allowed disabled:opacity-40 hover:bg-slate-50">
                    Previous
                </button>
                <button type="button" data-page="{{ $videoReviews->currentPage() + 1 }}" @if (! $videoReviews->hasMorePages()) disabled @endif
                        class="rounded-lg border border-slate-200 px-3 py-1.5 disabled:cursor-not-allowed disabled:opacity-40 hover:bg-slate-50">
                    Next
                </button>
            </div>
        </div>
    @endif
</div>
