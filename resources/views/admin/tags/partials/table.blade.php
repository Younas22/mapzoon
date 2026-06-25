<div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white">
    <table class="w-full min-w-[480px] text-left text-sm">
        <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
            <tr>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">Posts</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($tags as $tag)
                <tr>
                    <td class="px-4 py-3 font-medium text-ink">{{ $tag->name }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $tag->posts_count }}</td>
                    <td class="px-4 py-3 text-right">
                        <div class="inline-flex items-center gap-3">
                            <button type="button" class="text-sm font-medium text-primary-600 hover:text-primary-700" @click="openEdit({{ $tag->id }})">Edit</button>
                            <button type="button" class="text-sm font-medium text-rose-600 hover:text-rose-700" @click="confirmDelete({{ $tag->id }}, '{{ addslashes($tag->name) }}')">Delete</button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-4 py-10 text-center text-slate-400">No tags yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($tags->hasPages())
        <div class="flex items-center justify-between border-t border-slate-200 px-4 py-3 text-sm text-slate-500">
            <span>Page {{ $tags->currentPage() }} of {{ $tags->lastPage() }} ({{ $tags->total() }} tags)</span>
            <div class="flex gap-2">
                <button type="button" data-page="{{ $tags->currentPage() - 1 }}" @if ($tags->onFirstPage()) disabled @endif
                        class="rounded-lg border border-slate-200 px-3 py-1.5 disabled:cursor-not-allowed disabled:opacity-40 hover:bg-slate-50">
                    Previous
                </button>
                <button type="button" data-page="{{ $tags->currentPage() + 1 }}" @if (! $tags->hasMorePages()) disabled @endif
                        class="rounded-lg border border-slate-200 px-3 py-1.5 disabled:cursor-not-allowed disabled:opacity-40 hover:bg-slate-50">
                    Next
                </button>
            </div>
        </div>
    @endif
</div>
