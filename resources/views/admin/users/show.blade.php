<x-admin-layout :title="$user->name">
    <div x-data="userManager(@js(['baseUrl' => url('/admin/users'), 'indexUrl' => route('admin.users.index')]))" x-cloak class="mx-auto max-w-4xl space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-4">
                    @if ($user->avatarUrl())
                        <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="h-16 w-16 rounded-full object-cover">
                    @else
                        <span class="flex h-16 w-16 items-center justify-center rounded-full bg-primary-100 text-xl font-semibold text-primary-700">
                            {{ Str::upper(Str::substr($user->name, 0, 1)) }}
                        </span>
                    @endif

                    <div>
                        <h1 class="text-lg font-semibold text-ink">{{ $user->name }}</h1>
                        <p class="text-sm text-slate-500">{{ $user->email }}</p>
                        <div class="mt-1 flex items-center gap-2">
                            @if ($user->is_active)
                                <span class="rounded-full bg-primary-50 px-2 py-0.5 text-xs font-medium text-primary-700">Active</span>
                            @else
                                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">Inactive</span>
                            @endif
                            @if ($user->role)
                                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">{{ $user->role->name }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    @can('update', $user)
                        <a href="{{ route('admin.users.edit', $user) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">
                            Edit
                        </a>
                    @endcan
                    @can('delete', $user)
                        <button type="button" @click="confirmDelete({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                            Delete
                        </button>
                    @endcan
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Phone</p>
                <p class="mt-1 text-sm font-medium text-ink">{{ $user->phone ?: '—' }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Department</p>
                <p class="mt-1 text-sm font-medium text-ink">{{ $user->department ?: '—' }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Designation</p>
                <p class="mt-1 text-sm font-medium text-ink">{{ $user->designation ?: '—' }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Last Login</p>
                <p class="mt-1 text-sm font-medium text-ink">{{ $user->last_login_at?->diffForHumans() ?? 'Never' }}</p>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="mb-4 text-base font-semibold text-ink">Permissions</h2>

            <p class="mb-1 text-xs font-medium uppercase tracking-wide text-slate-400">From role: {{ $user->role?->name ?? 'None' }}</p>
            <div class="mb-4 flex flex-wrap gap-1.5">
                @forelse ($user->role?->permissions ?? [] as $permission)
                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs text-slate-600">{{ $permission->slug }}</span>
                @empty
                    <span class="text-sm text-slate-400">No role permissions.</span>
                @endforelse
            </div>

            <p class="mb-1 text-xs font-medium uppercase tracking-wide text-slate-400">Individual permissions</p>
            <div class="flex flex-wrap gap-1.5">
                @forelse ($user->directPermissions as $permission)
                    <span class="rounded-full bg-primary-50 px-2 py-0.5 text-xs text-primary-700">{{ $permission->slug }}</span>
                @empty
                    <span class="text-sm text-slate-400">No individual permissions assigned.</span>
                @endforelse
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="mb-1 text-base font-semibold text-ink">Assigned Projects</h2>
            <p class="text-sm text-slate-400">No projects assigned yet. This will populate once the Projects module is built.</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="mb-1 text-base font-semibold text-ink">Assigned Tasks</h2>
            <p class="text-sm text-slate-400">No tasks assigned yet. This will populate once the Tasks module is built.</p>
        </div>

        {{-- Delete confirmation modal --}}
        <div x-show="deleteModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/50" @click="deleteModalOpen = false"></div>

            <div class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl">
                <h2 class="text-lg font-semibold text-ink">Delete user?</h2>
                <p class="mt-2 text-sm text-slate-500">
                    Are you sure you want to delete <span class="font-medium text-ink" x-text="deleteTarget.name"></span>?
                    This cannot be undone.
                </p>

                <div class="mt-5 flex justify-end gap-3">
                    <button type="button" @click="deleteModalOpen = false" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">
                        Cancel
                    </button>
                    <button
                        type="button"
                        @click="deleteUser()"
                        :disabled="deleting"
                        class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700 disabled:opacity-60"
                    >
                        <span x-show="!deleting">Delete</span>
                        <span x-show="deleting">Deleting...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
