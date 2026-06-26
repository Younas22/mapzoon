@php
    $sort = request()->query('sort', 'created_at');
    $dir = request()->query('dir', 'desc');
    $arrow = fn (string $field) => $sort === $field ? ($dir === 'asc' ? '↑' : '↓') : '';

    $statusColors = [
        'pending' => 'bg-slate-100 text-slate-600',
        'in_progress' => 'bg-blue-50 text-blue-700',
        'review' => 'bg-amber-50 text-amber-700',
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
                <th class="cursor-pointer select-none px-4 py-3" data-sort="title">Task {{ $arrow('title') }}</th>
                <th class="cursor-pointer select-none px-4 py-3" data-sort="priority">Priority {{ $arrow('priority') }}</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Progress</th>
                <th class="px-4 py-3">Assigned To</th>
                <th class="cursor-pointer select-none px-4 py-3" data-sort="due_date">Due Date {{ $arrow('due_date') }}</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($tasks as $task)
                <tr>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.tasks.show', $task) }}" class="block hover:underline">
                            <span class="block font-medium text-ink">{{ $task->title }}</span>
                            @if ($task->project)
                                <span class="block text-xs text-slate-500">{{ $task->project->name }}</span>
                            @endif
                        </a>
                    </td>
                    <td class="px-4 py-3">
                        <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $priorityColors[$task->priority] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ $task->priorityLabel() }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $statusColors[$task->status] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ $task->statusLabel() }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <div class="h-1.5 w-20 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full bg-primary-500" style="width: {{ $task->progress }}%"></div>
                            </div>
                            <span class="text-xs text-slate-500">{{ $task->progress }}%</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-slate-500">{{ $task->assignedUser?->name ?? 'Unassigned' }}</td>
                    <td class="px-4 py-3 {{ $task->isOverdue() ? 'font-medium text-rose-600' : 'text-slate-500' }}">
                        {{ $task->due_date?->format('M d, Y') ?? '—' }}
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="inline-flex items-center gap-3">
                            <a href="{{ route('admin.tasks.show', $task) }}" class="text-sm font-medium text-slate-600 hover:text-ink">View</a>
                            @can('update', $task)
                                <button type="button" class="text-sm font-medium text-primary-600 hover:text-primary-700" @click="openEdit({{ $task->id }})">Edit</button>
                            @endcan
                            @can('delete', $task)
                                <button type="button" class="text-sm font-medium text-rose-600 hover:text-rose-700"
                                        @click="confirmDelete({{ $task->id }}, '{{ addslashes($task->title) }}')">
                                    Delete
                                </button>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-10 text-center text-slate-400">No tasks found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($tasks->hasPages())
        <div class="flex items-center justify-between border-t border-slate-200 px-4 py-3 text-sm text-slate-500">
            <span>Page {{ $tasks->currentPage() }} of {{ $tasks->lastPage() }} ({{ $tasks->total() }} tasks)</span>
            <div class="flex gap-2">
                <button type="button" data-page="{{ $tasks->currentPage() - 1 }}" @if ($tasks->onFirstPage()) disabled @endif
                        class="rounded-lg border border-slate-200 px-3 py-1.5 disabled:cursor-not-allowed disabled:opacity-40 hover:bg-slate-50">
                    Previous
                </button>
                <button type="button" data-page="{{ $tasks->currentPage() + 1 }}" @if (! $tasks->hasMorePages()) disabled @endif
                        class="rounded-lg border border-slate-200 px-3 py-1.5 disabled:cursor-not-allowed disabled:opacity-40 hover:bg-slate-50">
                    Next
                </button>
            </div>
        </div>
    @endif
</div>
