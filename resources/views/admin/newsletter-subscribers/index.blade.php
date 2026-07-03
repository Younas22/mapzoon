<x-admin-layout title="Newsletter Subscribers">
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-sm font-medium text-slate-500">Total Subscribers</p>
            <p class="mt-2 text-2xl font-bold text-ink">{{ $stats['total'] }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-sm font-medium text-slate-500">New This Month</p>
            <p class="mt-2 text-2xl font-bold text-primary-600">{{ $stats['this_month'] }}</p>
        </div>
    </div>

    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" class="w-full sm:w-64">
            <input
                type="search"
                name="q"
                value="{{ request('q') }}"
                placeholder="Search email..."
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200"
            >
        </form>

        <a
            href="{{ route('admin.newsletter-subscribers.export') }}?{{ http_build_query(request()->only('q')) }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50"
        >
            Export CSV
        </a>
    </div>

    <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white">
        <table class="w-full min-w-[560px] text-left text-sm">
            <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Source</th>
                    <th class="px-4 py-3">Subscribed At</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($subscribers as $subscriber)
                    <tr>
                        <td class="px-4 py-3 font-medium text-ink">{{ $subscriber->email }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $subscriber->source ?: '—' }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $subscriber->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            @can('delete', $subscriber)
                                <form method="POST" action="{{ route('admin.newsletter-subscribers.destroy', $subscriber) }}"
                                      onsubmit="return confirm('Remove {{ addslashes($subscriber->email) }} from the subscriber list?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-medium text-rose-600 hover:text-rose-700">Delete</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-10 text-center text-slate-400">No subscribers yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($subscribers->hasPages())
            <div class="border-t border-slate-200 px-4 py-3">
                {{ $subscribers->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
