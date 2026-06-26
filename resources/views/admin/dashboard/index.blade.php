@php
    $taskStatusColors = [
        'pending' => 'bg-slate-100 text-slate-600',
        'in_progress' => 'bg-blue-50 text-blue-700',
        'review' => 'bg-amber-50 text-amber-700',
        'completed' => 'bg-primary-50 text-primary-700',
        'cancelled' => 'bg-rose-50 text-rose-700',
    ];

    $leadStatusColors = [
        'new' => 'bg-slate-100 text-slate-600',
        'contacted' => 'bg-amber-50 text-amber-700',
        'qualified' => 'bg-blue-50 text-blue-700',
        'proposal_sent' => 'bg-violet-50 text-violet-700',
        'won' => 'bg-primary-50 text-primary-700',
        'lost' => 'bg-rose-50 text-rose-700',
    ];
@endphp

<x-admin-layout title="Dashboard">
    <div class="mb-6 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-lg font-semibold text-ink">Welcome back, {{ auth()->user()->name }}</h1>
            <p class="text-sm text-slate-500">
                Signed in as <span class="font-medium text-ink">{{ auth()->user()->role?->name ?? 'No role assigned' }}</span>
            </p>
        </div>
    </div>

    @if (count($cards))
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($cards as $card)
                @if ($card['url'])
                    <a href="{{ $card['url'] }}" class="rounded-2xl border border-slate-200 bg-white p-5 transition hover:border-primary-200 hover:shadow-md">
                        <p class="text-sm font-medium text-slate-500">{{ $card['label'] }}</p>
                        <p class="mt-2 text-2xl font-bold text-ink">{{ $card['value'] }}</p>
                    </a>
                @else
                    <div class="rounded-2xl border border-slate-200 bg-white p-5">
                        <p class="text-sm font-medium text-slate-500">{{ $card['label'] }}</p>
                        <p class="mt-2 text-2xl font-bold text-ink">{{ $card['value'] }}</p>
                    </div>
                @endif
            @endforeach
        </div>
    @endif

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Recent Activities --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 lg:col-span-2">
            <h2 class="mb-4 text-base font-semibold text-ink">Recent Activities</h2>

            <ol class="space-y-3">
                @forelse ($recentActivities as $activity)
                    <li class="flex items-start gap-3 border-l-2 border-slate-100 pl-3">
                        <div class="flex-1">
                            <p class="text-sm text-slate-600">
                                @if ($activity['url'])
                                    <a href="{{ $activity['url'] }}" class="hover:underline">{{ $activity['description'] }}</a>
                                @else
                                    {{ $activity['description'] }}
                                @endif
                            </p>
                            <p class="text-xs text-slate-400">{{ $activity['badge'] }} · {{ $activity['created_at']->diffForHumans() }}</p>
                        </div>
                    </li>
                @empty
                    <p class="text-sm text-slate-400">No recent activity yet.</p>
                @endforelse
            </ol>
        </div>

        {{-- Upcoming Deadlines --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="mb-4 text-base font-semibold text-ink">Upcoming Deadlines</h2>

            <ul class="space-y-3">
                @forelse ($upcomingDeadlines as $deadline)
                    <li>
                        <a href="{{ $deadline['url'] }}" class="flex items-center justify-between gap-3 rounded-lg border border-slate-100 px-3 py-2 hover:border-primary-200">
                            <div>
                                <span class="block text-sm font-medium text-ink">{{ $deadline['title'] }}</span>
                                <span class="block text-xs text-slate-400">{{ $deadline['badge'] }}</span>
                            </div>
                            <span class="flex-none text-xs font-medium {{ $deadline['overdue'] ? 'text-rose-600' : 'text-slate-500' }}">
                                {{ $deadline['date']->format('M d, Y') }}
                            </span>
                        </a>
                    </li>
                @empty
                    <p class="text-sm text-slate-400">No upcoming deadlines.</p>
                @endforelse
            </ul>
        </div>

        {{-- Recent Tasks --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 lg:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-base font-semibold text-ink">Recent Tasks</h2>
                @can('viewAny', App\Models\Task::class)
                    <a href="{{ route('admin.tasks.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">View all &rarr;</a>
                @endcan
            </div>

            <div class="space-y-2">
                @forelse ($recentTasks as $task)
                    <a href="{{ route('admin.tasks.show', $task) }}" class="flex items-center justify-between gap-3 rounded-lg border border-slate-100 px-3 py-2 hover:border-primary-200">
                        <div>
                            <span class="block text-sm font-medium text-ink">{{ $task->title }}</span>
                            <span class="block text-xs text-slate-400">{{ $task->assignedUser?->name ?? 'Unassigned' }}</span>
                        </div>
                        <span class="flex-none rounded-full px-2 py-0.5 text-xs font-medium {{ $taskStatusColors[$task->status] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ $task->statusLabel() }}
                        </span>
                    </a>
                @empty
                    <p class="text-sm text-slate-400">No tasks yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Recent Leads --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-base font-semibold text-ink">Recent Leads</h2>
                @can('viewAny', App\Models\Lead::class)
                    <a href="{{ route('admin.leads.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">View all &rarr;</a>
                @endcan
            </div>

            <div class="space-y-2">
                @forelse ($recentLeads as $lead)
                    <a href="{{ route('admin.leads.show', $lead) }}" class="flex items-center justify-between gap-3 rounded-lg border border-slate-100 px-3 py-2 hover:border-primary-200">
                        <div>
                            <span class="block text-sm font-medium text-ink">{{ $lead->name }}</span>
                            <span class="block text-xs text-slate-400">{{ $lead->business_name ?: $lead->email }}</span>
                        </div>
                        <span class="flex-none rounded-full px-2 py-0.5 text-xs font-medium {{ $leadStatusColors[$lead->status] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ $lead->statusLabel() }}
                        </span>
                    </a>
                @empty
                    <p class="text-sm text-slate-400">No leads yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-admin-layout>
