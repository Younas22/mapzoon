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
    <table class="w-full min-w-[760px] text-left text-sm">
        <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
            <tr>
                <th class="cursor-pointer select-none px-4 py-3" data-sort="display_order">Order {{ $arrow('display_order') }}</th>
                <th class="cursor-pointer select-none px-4 py-3" data-sort="name">Name {{ $arrow('name') }}</th>
                <th class="px-4 py-3">Designation</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Homepage</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($teamMembers as $member)
                <tr>
                    <td class="px-4 py-3 text-slate-500">{{ $member->display_order }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            @if ($member->photoUrl())
                                <img src="{{ $member->photoUrl() }}" alt="{{ $member->name }}" class="h-9 w-9 flex-none rounded-full object-cover">
                            @else
                                <span class="flex h-9 w-9 flex-none items-center justify-center rounded-full bg-primary-100 text-xs font-bold text-primary-700">{{ $member->initials() }}</span>
                            @endif
                            <div>
                                <span class="block font-medium text-ink">{{ $member->name }}</span>
                                <span class="block text-xs text-slate-500">{{ $member->email ?: '—' }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-slate-500">{{ $member->designation }}</td>
                    <td class="px-4 py-3">
                        <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $statusColors[$member->status] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ $member->statusLabel() }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @if ($member->is_visible_on_homepage)
                            <span class="rounded-full bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700">Visible</span>
                        @else
                            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">Hidden</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="inline-flex items-center gap-3">
                            @can('update', $member)
                                <button type="button" class="text-sm font-medium text-primary-600 hover:text-primary-700" @click="openEdit({{ $member->id }})">Edit</button>
                            @endcan
                            @can('delete', $member)
                                <button type="button" class="text-sm font-medium text-rose-600 hover:text-rose-700"
                                        @click="confirmDelete({{ $member->id }}, '{{ addslashes($member->name) }}')">
                                    Delete
                                </button>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-10 text-center text-slate-400">No team members found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($teamMembers->hasPages())
        <div class="flex items-center justify-between border-t border-slate-200 px-4 py-3 text-sm text-slate-500">
            <span>Page {{ $teamMembers->currentPage() }} of {{ $teamMembers->lastPage() }} ({{ $teamMembers->total() }} members)</span>
            <div class="flex gap-2">
                <button type="button" data-page="{{ $teamMembers->currentPage() - 1 }}" @if ($teamMembers->onFirstPage()) disabled @endif
                        class="rounded-lg border border-slate-200 px-3 py-1.5 disabled:cursor-not-allowed disabled:opacity-40 hover:bg-slate-50">
                    Previous
                </button>
                <button type="button" data-page="{{ $teamMembers->currentPage() + 1 }}" @if (! $teamMembers->hasMorePages()) disabled @endif
                        class="rounded-lg border border-slate-200 px-3 py-1.5 disabled:cursor-not-allowed disabled:opacity-40 hover:bg-slate-50">
                    Next
                </button>
            </div>
        </div>
    @endif
</div>
