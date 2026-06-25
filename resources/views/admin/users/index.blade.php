<x-admin-layout title="Users">
    <div x-data="userManager(@js(['baseUrl' => url('/admin/users'), 'indexUrl' => route('admin.users.index')]))" x-cloak>
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex flex-col gap-3 sm:flex-row">
                <input
                    type="search"
                    data-table-search
                    placeholder="Search by name or email..."
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm sm:w-72 focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200"
                >
            </div>

            @can('create', App\Models\User::class)
                <a
                    href="{{ route('admin.users.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700"
                >
                    + Add User
                </a>
            @endcan
        </div>

        <div data-table data-table-url="{{ route('admin.users.data') }}">
            <div data-table-body>
                @include('admin.users.partials.table', ['users' => $users])
            </div>
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
