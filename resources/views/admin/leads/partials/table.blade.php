@php
    $sort = request()->query('sort', 'created_at');
    $dir = request()->query('dir', 'desc');
    $arrow = fn (string $field) => $sort === $field ? ($dir === 'asc' ? '↑' : '↓') : '';

    $statusColors = [
        'new' => 'bg-slate-100 text-slate-600',
        'contacted' => 'bg-amber-50 text-amber-700',
        'qualified' => 'bg-blue-50 text-blue-700',
        'proposal_sent' => 'bg-violet-50 text-violet-700',
        'won' => 'bg-primary-50 text-primary-700',
        'lost' => 'bg-rose-50 text-rose-700',
    ];
@endphp

<div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white">
    <table class="w-full min-w-[920px] text-left text-sm">
        <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
            <tr>
                <th class="cursor-pointer select-none px-4 py-3" data-sort="name">Lead {{ $arrow('name') }}</th>
                <th class="px-4 py-3">Service</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Source</th>
                <th class="px-4 py-3">Assigned To</th>
                <th class="cursor-pointer select-none px-4 py-3" data-sort="follow_up_date">Follow-up {{ $arrow('follow_up_date') }}</th>
                <th class="cursor-pointer select-none px-4 py-3" data-sort="created_at">Created {{ $arrow('created_at') }}</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($leads as $lead)
                <tr>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.leads.show', $lead) }}" class="block hover:underline">
                            <span class="block font-medium text-ink">{{ $lead->name }}</span>
                            <span class="block text-xs text-slate-500">{{ $lead->email }} · {{ $lead->phone }}</span>
                            @if ($lead->business_name)
                                <span class="block text-xs text-slate-400">{{ $lead->business_name }}</span>
                            @endif
                        </a>
                    </td>
                    <td class="px-4 py-3 text-slate-500">{{ $lead->service ?: '—' }}</td>
                    <td class="px-4 py-3">
                        <select
                            @change="changeStatus({{ $lead->id }}, $event.target.value)"
                            class="rounded-lg border-0 px-2 py-1 text-xs font-medium {{ $statusColors[$lead->status] ?? 'bg-slate-100 text-slate-600' }}"
                        >
                            @foreach (\App\Models\Lead::STATUSES as $value => $label)
                                <option value="{{ $value }}" @selected($lead->status === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-4 py-3 text-slate-500">{{ $lead->sourceLabel() }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $lead->assignedUser?->name ?? 'Unassigned' }}</td>
                    <td class="px-4 py-3 {{ $lead->isFollowUpOverdue() ? 'font-medium text-rose-600' : 'text-slate-500' }}">
                        {{ $lead->follow_up_date?->format('M d, Y') ?? '—' }}
                    </td>
                    <td class="px-4 py-3 text-slate-500">{{ $lead->created_at->format('M d, Y') }}</td>
                    <td class="px-4 py-3 text-right">
                        <div class="inline-flex items-center gap-3">
                            <a href="{{ route('admin.leads.show', $lead) }}" class="text-sm font-medium text-slate-600 hover:text-ink">View</a>
                            @can('update', $lead)
                                <button type="button" class="text-sm font-medium text-primary-600 hover:text-primary-700"
                                        @click="openEdit({{ $lead->id }})">
                                    Edit
                                </button>
                            @endcan
                            @can('delete', $lead)
                                <button type="button" class="text-sm font-medium text-rose-600 hover:text-rose-700"
                                        @click="confirmDelete({{ $lead->id }}, '{{ addslashes($lead->name) }}')">
                                    Delete
                                </button>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-4 py-10 text-center text-slate-400">No leads found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($leads->hasPages())
        <div class="flex items-center justify-between border-t border-slate-200 px-4 py-3 text-sm text-slate-500">
            <span>Page {{ $leads->currentPage() }} of {{ $leads->lastPage() }} ({{ $leads->total() }} leads)</span>
            <div class="flex gap-2">
                <button type="button" data-page="{{ $leads->currentPage() - 1 }}" @if ($leads->onFirstPage()) disabled @endif
                        class="rounded-lg border border-slate-200 px-3 py-1.5 disabled:cursor-not-allowed disabled:opacity-40 hover:bg-slate-50">
                    Previous
                </button>
                <button type="button" data-page="{{ $leads->currentPage() + 1 }}" @if (! $leads->hasMorePages()) disabled @endif
                        class="rounded-lg border border-slate-200 px-3 py-1.5 disabled:cursor-not-allowed disabled:opacity-40 hover:bg-slate-50">
                    Next
                </button>
            </div>
        </div>
    @endif
</div>
