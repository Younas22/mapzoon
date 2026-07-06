@php
    $sort = request()->query('sort', 'created_at');
    $dir = request()->query('dir', 'desc');
    $arrow = fn (string $field) => $sort === $field ? ($dir === 'asc' ? '↑' : '↓') : '';

    $statusColors = [
        'new' => 'bg-slate-100 text-slate-600',
        'reviewed' => 'bg-amber-50 text-amber-700',
        'shortlisted' => 'bg-primary-50 text-primary-700',
        'rejected' => 'bg-rose-50 text-rose-700',
    ];
@endphp

<div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white">
    <table class="w-full min-w-[880px] text-left text-sm">
        <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
            <tr>
                <th class="cursor-pointer select-none px-4 py-3" data-sort="first_name">Applicant {{ $arrow('first_name') }}</th>
                <th class="cursor-pointer select-none px-4 py-3" data-sort="position">Position {{ $arrow('position') }}</th>
                <th class="px-4 py-3">City</th>
                <th class="px-4 py-3">Status</th>
                <th class="cursor-pointer select-none px-4 py-3" data-sort="created_at">Applied {{ $arrow('created_at') }}</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($applications as $application)
                <tr>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.job-applications.show', $application) }}" class="block hover:underline">
                            <span class="block font-medium text-ink">{{ $application->fullName() }}</span>
                            <span class="block text-xs text-slate-500">{{ $application->email }} · {{ $application->phone }}</span>
                        </a>
                    </td>
                    <td class="px-4 py-3 text-slate-500">{{ $application->position }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $application->city ?: '—' }}</td>
                    <td class="px-4 py-3">
                        <select
                            @change="changeStatus({{ $application->id }}, $event.target.value)"
                            class="rounded-lg border-0 px-2 py-1 text-xs font-medium {{ $statusColors[$application->status] ?? 'bg-slate-100 text-slate-600' }}"
                        >
                            @foreach (\App\Models\JobApplication::STATUSES as $value => $label)
                                <option value="{{ $value }}" @selected($application->status === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-4 py-3 text-slate-500">{{ $application->created_at->format('M d, Y') }}</td>
                    <td class="px-4 py-3 text-right">
                        <div class="inline-flex items-center gap-3">
                            <a href="{{ route('admin.job-applications.show', $application) }}" class="text-sm font-medium text-slate-600 hover:text-ink">View</a>
                            @can('delete', $application)
                                <button type="button" class="text-sm font-medium text-rose-600 hover:text-rose-700"
                                        @click="confirmDelete({{ $application->id }}, '{{ addslashes($application->fullName()) }}')">
                                    Delete
                                </button>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-10 text-center text-slate-400">No job applications found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($applications->hasPages())
        <div class="flex items-center justify-between border-t border-slate-200 px-4 py-3 text-sm text-slate-500">
            <span>Page {{ $applications->currentPage() }} of {{ $applications->lastPage() }} ({{ $applications->total() }} applications)</span>
            <div class="flex gap-2">
                <button type="button" data-page="{{ $applications->currentPage() - 1 }}" @if ($applications->onFirstPage()) disabled @endif
                        class="rounded-lg border border-slate-200 px-3 py-1.5 disabled:cursor-not-allowed disabled:opacity-40 hover:bg-slate-50">
                    Previous
                </button>
                <button type="button" data-page="{{ $applications->currentPage() + 1 }}" @if (! $applications->hasMorePages()) disabled @endif
                        class="rounded-lg border border-slate-200 px-3 py-1.5 disabled:cursor-not-allowed disabled:opacity-40 hover:bg-slate-50">
                    Next
                </button>
            </div>
        </div>
    @endif
</div>
