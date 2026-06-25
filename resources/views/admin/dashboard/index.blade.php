<x-admin-layout title="Dashboard">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-sm font-medium text-slate-500">Total Roles</p>
            <p class="mt-2 text-2xl font-bold text-ink">{{ $stats['roles'] }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-sm font-medium text-slate-500">Total Permissions</p>
            <p class="mt-2 text-2xl font-bold text-ink">{{ $stats['permissions'] }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-sm font-medium text-slate-500">Total Users</p>
            <p class="mt-2 text-2xl font-bold text-ink">{{ $stats['users'] }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-sm font-medium text-slate-500">Active Users</p>
            <p class="mt-2 text-2xl font-bold text-ink">{{ $stats['active_users'] }}</p>
        </div>
    </div>

    <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6">
        <h2 class="text-base font-semibold text-ink">Welcome back, {{ auth()->user()->name }}</h2>
        <p class="mt-1 text-sm text-slate-500">
            You're signed in as <span class="font-medium text-ink">{{ auth()->user()->role?->name ?? 'No role assigned' }}</span>.
            More modules (Users, Clients, Projects, Tasks, CMS) will appear here as they're built.
        </p>
    </div>
</x-admin-layout>
