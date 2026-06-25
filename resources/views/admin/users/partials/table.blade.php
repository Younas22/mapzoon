@php
    $sort = request()->query('sort', 'name');
    $dir = request()->query('dir', 'asc');
    $arrow = fn (string $field) => $sort === $field ? ($dir === 'asc' ? '↑' : '↓') : '';
@endphp

<div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white">
    <table class="w-full min-w-[760px] text-left text-sm">
        <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
            <tr>
                <th class="cursor-pointer select-none px-4 py-3" data-sort="name">User {{ $arrow('name') }}</th>
                <th class="px-4 py-3">Role</th>
                <th class="px-4 py-3">Department</th>
                <th class="px-4 py-3">Status</th>
                <th class="cursor-pointer select-none px-4 py-3" data-sort="last_login_at">Last Login {{ $arrow('last_login_at') }}</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($users as $user)
                <tr>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.users.show', $user) }}" class="flex items-center gap-3 hover:underline">
                            @if ($user->avatarUrl())
                                <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="h-9 w-9 rounded-full object-cover">
                            @else
                                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-primary-100 text-sm font-semibold text-primary-700">
                                    {{ Str::upper(Str::substr($user->name, 0, 1)) }}
                                </span>
                            @endif
                            <span>
                                <span class="block font-medium text-ink">{{ $user->name }}</span>
                                <span class="block text-xs text-slate-500">{{ $user->email }}</span>
                            </span>
                        </a>
                    </td>
                    <td class="px-4 py-3 text-slate-500">{{ $user->role?->name ?? '—' }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $user->department ?: '—' }}</td>
                    <td class="px-4 py-3">
                        @if ($user->is_active)
                            <span class="rounded-full bg-primary-50 px-2 py-0.5 text-xs font-medium text-primary-700">Active</span>
                        @else
                            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">Inactive</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-slate-500">{{ $user->last_login_at?->diffForHumans() ?? 'Never' }}</td>
                    <td class="px-4 py-3 text-right">
                        <div class="inline-flex items-center gap-3">
                            <a href="{{ route('admin.users.show', $user) }}" class="text-sm font-medium text-slate-600 hover:text-ink">View</a>
                            @can('update', $user)
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">Edit</a>
                            @endcan
                            @can('delete', $user)
                                <button type="button" class="text-sm font-medium text-rose-600 hover:text-rose-700"
                                        @click="confirmDelete({{ $user->id }}, '{{ addslashes($user->name) }}')">
                                    Delete
                                </button>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-10 text-center text-slate-400">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($users->hasPages())
        <div class="flex items-center justify-between border-t border-slate-200 px-4 py-3 text-sm text-slate-500">
            <span>Page {{ $users->currentPage() }} of {{ $users->lastPage() }} ({{ $users->total() }} users)</span>
            <div class="flex gap-2">
                <button type="button" data-page="{{ $users->currentPage() - 1 }}" @if ($users->onFirstPage()) disabled @endif
                        class="rounded-lg border border-slate-200 px-3 py-1.5 disabled:cursor-not-allowed disabled:opacity-40 hover:bg-slate-50">
                    Previous
                </button>
                <button type="button" data-page="{{ $users->currentPage() + 1 }}" @if (! $users->hasMorePages()) disabled @endif
                        class="rounded-lg border border-slate-200 px-3 py-1.5 disabled:cursor-not-allowed disabled:opacity-40 hover:bg-slate-50">
                    Next
                </button>
            </div>
        </div>
    @endif
</div>
