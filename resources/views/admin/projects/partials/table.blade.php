@php
    $sort = request()->query('sort', 'created_at');
    $dir = request()->query('dir', 'desc');
    $arrow = fn (string $field) => $sort === $field ? ($dir === 'asc' ? '↑' : '↓') : '';

    $statusColors = [
        'planning' => 'bg-slate-100 text-slate-600',
        'in_progress' => 'bg-blue-50 text-blue-700',
        'on_hold' => 'bg-amber-50 text-amber-700',
        'completed' => 'bg-primary-50 text-primary-700',
        'cancelled' => 'bg-rose-50 text-rose-700',
    ];

    $priorityColors = [
        'low' => 'bg-slate-100 text-slate-600',
        'medium' => 'bg-blue-50 text-blue-700',
        'high' => 'bg-amber-50 text-amber-700',
        'urgent' => 'bg-rose-50 text-rose-700',
    ];
@endphp

<div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white">
    <table class="w-full min-w-[920px] text-left text-sm">
        <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
            <tr>
                <th class="cursor-pointer select-none px-4 py-3" data-sort="name">Project {{ $arrow('name') }}</th>
                <th class="px-4 py-3">Client</th>
                <th class="cursor-pointer select-none px-4 py-3" data-sort="priority">Priority {{ $arrow('priority') }}</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Progress</th>
                <th class="cursor-pointer select-none px-4 py-3" data-sort="end_date">End Date {{ $arrow('end_date') }}</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($projects as $project)
                <tr>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.projects.show', $project) }}" class="block hover:underline">
                            <span class="block font-medium text-ink">{{ $project->name }}</span>
                            <span class="block text-xs text-slate-500">{{ $project->project_type ?: '—' }}</span>
                        </a>
                    </td>
                    <td class="px-4 py-3 text-slate-500">{{ $project->client->displayName() }}</td>
                    <td class="px-4 py-3">
                        <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $priorityColors[$project->priority] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ $project->priorityLabel() }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $statusColors[$project->status] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ $project->statusLabel() }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <div class="h-1.5 w-20 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full bg-primary-500" style="width: {{ $project->progress }}%"></div>
                            </div>
                            <span class="text-xs text-slate-500">{{ $project->progress }}%</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 {{ $project->isOverdue() ? 'font-medium text-rose-600' : 'text-slate-500' }}">
                        {{ $project->end_date?->format('M d, Y') ?? '—' }}
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="inline-flex items-center gap-3">
                            <a href="{{ route('admin.projects.show', $project) }}" class="text-sm font-medium text-slate-600 hover:text-ink">View</a>
                            @can('update', $project)
                                <button type="button" class="text-sm font-medium text-primary-600 hover:text-primary-700" @click="openEdit({{ $project->id }})">Edit</button>
                            @endcan
                            @can('delete', $project)
                                <button type="button" class="text-sm font-medium text-rose-600 hover:text-rose-700"
                                        @click="confirmDelete({{ $project->id }}, '{{ addslashes($project->name) }}')">
                                    Delete
                                </button>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-10 text-center text-slate-400">No projects found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($projects->hasPages())
        <div class="flex items-center justify-between border-t border-slate-200 px-4 py-3 text-sm text-slate-500">
            <span>Page {{ $projects->currentPage() }} of {{ $projects->lastPage() }} ({{ $projects->total() }} projects)</span>
            <div class="flex gap-2">
                <button type="button" data-page="{{ $projects->currentPage() - 1 }}" @if ($projects->onFirstPage()) disabled @endif
                        class="rounded-lg border border-slate-200 px-3 py-1.5 disabled:cursor-not-allowed disabled:opacity-40 hover:bg-slate-50">
                    Previous
                </button>
                <button type="button" data-page="{{ $projects->currentPage() + 1 }}" @if (! $projects->hasMorePages()) disabled @endif
                        class="rounded-lg border border-slate-200 px-3 py-1.5 disabled:cursor-not-allowed disabled:opacity-40 hover:bg-slate-50">
                    Next
                </button>
            </div>
        </div>
    @endif
</div>
