@php
    $sort = request()->query('sort', 'created_at');
    $dir = request()->query('dir', 'desc');
    $arrow = fn (string $field) => $sort === $field ? ($dir === 'asc' ? '↑' : '↓') : '';

    $statusColors = [
        'active' => 'bg-primary-50 text-primary-700',
        'inactive' => 'bg-slate-100 text-slate-600',
        'on_hold' => 'bg-amber-50 text-amber-700',
    ];
@endphp

<div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white">
    <table class="w-full min-w-[860px] text-left text-sm">
        <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
            <tr>
                <th class="cursor-pointer select-none px-4 py-3" data-sort="company_name">Client {{ $arrow('company_name') }}</th>
                <th class="px-4 py-3">Type</th>
                <th class="px-4 py-3">Industry</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Team</th>
                <th class="cursor-pointer select-none px-4 py-3" data-sort="created_at">Added {{ $arrow('created_at') }}</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($clients as $client)
                <tr>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.clients.show', $client) }}" class="block hover:underline">
                            <span class="block font-medium text-ink">{{ $client->displayName() }}</span>
                            <span class="block text-xs text-slate-500">{{ $client->email }} · {{ $client->phone }}</span>
                        </a>
                    </td>
                    <td class="px-4 py-3 text-slate-500">{{ $client->clientTypeLabel() }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $client->industry ?: '—' }}</td>
                    <td class="px-4 py-3">
                        <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $statusColors[$client->status] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ $client->statusLabel() }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-slate-500">{{ $client->team_members_count }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $client->created_at->format('M d, Y') }}</td>
                    <td class="px-4 py-3 text-right">
                        <div class="inline-flex items-center gap-3">
                            <a href="{{ route('admin.clients.show', $client) }}" class="text-sm font-medium text-slate-600 hover:text-ink">View</a>
                            @can('update', $client)
                                <button type="button" class="text-sm font-medium text-primary-600 hover:text-primary-700" @click="openEdit({{ $client->id }})">Edit</button>
                            @endcan
                            @can('delete', $client)
                                <button type="button" class="text-sm font-medium text-rose-600 hover:text-rose-700"
                                        @click="confirmDelete({{ $client->id }}, '{{ addslashes($client->displayName()) }}')">
                                    Delete
                                </button>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-10 text-center text-slate-400">No clients found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($clients->hasPages())
        <div class="flex items-center justify-between border-t border-slate-200 px-4 py-3 text-sm text-slate-500">
            <span>Page {{ $clients->currentPage() }} of {{ $clients->lastPage() }} ({{ $clients->total() }} clients)</span>
            <div class="flex gap-2">
                <button type="button" data-page="{{ $clients->currentPage() - 1 }}" @if ($clients->onFirstPage()) disabled @endif
                        class="rounded-lg border border-slate-200 px-3 py-1.5 disabled:cursor-not-allowed disabled:opacity-40 hover:bg-slate-50">
                    Previous
                </button>
                <button type="button" data-page="{{ $clients->currentPage() + 1 }}" @if (! $clients->hasMorePages()) disabled @endif
                        class="rounded-lg border border-slate-200 px-3 py-1.5 disabled:cursor-not-allowed disabled:opacity-40 hover:bg-slate-50">
                    Next
                </button>
            </div>
        </div>
    @endif
</div>
