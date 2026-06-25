@php
    $statusColors = [
        'pending' => 'bg-slate-100 text-slate-600',
        'in_progress' => 'bg-blue-50 text-blue-700',
        'review' => 'bg-amber-50 text-amber-700',
        'completed' => 'bg-primary-50 text-primary-700',
        'cancelled' => 'bg-rose-50 text-rose-700',
    ];
@endphp

<x-admin-layout title="My Tasks">
    <div class="mb-4 flex flex-wrap gap-2">
        <a href="{{ route('admin.tasks.mine') }}"
           class="rounded-lg border px-3 py-1.5 text-sm font-medium {{ request('status') ? 'border-slate-300 text-slate-600 hover:bg-slate-50' : 'border-primary-200 bg-primary-50 text-primary-700' }}">
            All
        </a>
        @foreach (\App\Models\Task::STATUSES as $value => $label)
            <a href="{{ route('admin.tasks.mine', ['status' => $value]) }}"
               class="rounded-lg border px-3 py-1.5 text-sm font-medium {{ request('status') === $value ? 'border-primary-200 bg-primary-50 text-primary-700' : 'border-slate-300 text-slate-600 hover:bg-slate-50' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="space-y-3">
        @forelse ($tasks as $task)
            <a href="{{ route('admin.tasks.show', $task) }}" class="block rounded-2xl border border-slate-200 bg-white p-5 transition hover:border-primary-200 hover:shadow-md">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="font-medium text-ink">{{ $task->title }}</p>
                        <p class="mt-1 text-sm text-slate-500">Created by {{ $task->creator?->name ?? 'Unknown' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $statusColors[$task->status] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ $task->statusLabel() }}
                        </span>
                        @if ($task->isOverdue())
                            <span class="rounded-full bg-rose-50 px-2 py-0.5 text-xs font-medium text-rose-700">Overdue</span>
                        @endif
                    </div>
                </div>

                <div class="mt-4 flex items-center gap-3">
                    <div class="h-1.5 flex-1 max-w-xs overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full bg-primary-500" style="width: {{ $task->progress }}%"></div>
                    </div>
                    <span class="text-xs text-slate-500">{{ $task->progress }}%</span>
                    @if ($task->due_date)
                        <span class="ml-auto text-xs text-slate-500">Due {{ $task->due_date->format('M d, Y') }}</span>
                    @endif
                </div>
            </a>
        @empty
            <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center text-slate-400">
                No tasks assigned to you yet.
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $tasks->links() }}
    </div>
</x-admin-layout>
