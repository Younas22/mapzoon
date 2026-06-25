@php
    $sort = request()->query('sort', 'name');
    $dir = request()->query('dir', 'asc');
    $arrow = fn (string $field) => $sort === $field ? ($dir === 'asc' ? '↑' : '↓') : '';
@endphp

<div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white">
    <table class="w-full min-w-[640px] text-left text-sm">
        <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
            <tr>
                <th class="cursor-pointer select-none px-4 py-3" data-sort="name">Name {{ $arrow('name') }}</th>
                <th class="px-4 py-3">Description</th>
                <th class="px-4 py-3">Permissions</th>
                <th class="px-4 py-3">Users</th>
                <th class="cursor-pointer select-none px-4 py-3" data-sort="created_at">Created {{ $arrow('created_at') }}</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($roles as $role)
                <tr>
                    <td class="px-4 py-3 font-medium text-ink">
                        {{ $role->name }}
                        @if ($role->is_system)
                            <span class="ml-2 rounded-full bg-amber-50 px-2 py-0.5 text-xs font-medium text-amber-700">System</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-slate-500">{{ $role->description ?: '—' }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $role->permissions_count }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $role->users_count }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $role->created_at->format('M d, Y') }}</td>
                    <td class="px-4 py-3 text-right">
                        @if ($role->is_system)
                            <span class="text-xs italic text-slate-400">Protected</span>
                        @else
                            <div class="inline-flex items-center gap-3">
                                @can('update', $role)
                                    <button type="button" class="text-sm font-medium text-primary-600 hover:text-primary-700"
                                            @click="openEdit({{ $role->id }})">
                                        Edit
                                    </button>
                                @endcan
                                @can('delete', $role)
                                    <button type="button" class="text-sm font-medium text-rose-600 hover:text-rose-700"
                                            @click="confirmDelete({{ $role->id }}, '{{ addslashes($role->name) }}')">
                                        Delete
                                    </button>
                                @endcan
                            </div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-10 text-center text-slate-400">No roles found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($roles->hasPages())
        <div class="flex items-center justify-between border-t border-slate-200 px-4 py-3 text-sm text-slate-500">
            <span>Page {{ $roles->currentPage() }} of {{ $roles->lastPage() }} ({{ $roles->total() }} roles)</span>
            <div class="flex gap-2">
                <button type="button" data-page="{{ $roles->currentPage() - 1 }}" @if ($roles->onFirstPage()) disabled @endif
                        class="rounded-lg border border-slate-200 px-3 py-1.5 disabled:cursor-not-allowed disabled:opacity-40 hover:bg-slate-50">
                    Previous
                </button>
                <button type="button" data-page="{{ $roles->currentPage() + 1 }}" @if (! $roles->hasMorePages()) disabled @endif
                        class="rounded-lg border border-slate-200 px-3 py-1.5 disabled:cursor-not-allowed disabled:opacity-40 hover:bg-slate-50">
                    Next
                </button>
            </div>
        </div>
    @endif
</div>
