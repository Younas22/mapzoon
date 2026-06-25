@php
    $statusColors = [
        'new' => 'bg-slate-100 text-slate-600',
        'contacted' => 'bg-amber-50 text-amber-700',
        'qualified' => 'bg-blue-50 text-blue-700',
        'proposal_sent' => 'bg-violet-50 text-violet-700',
        'won' => 'bg-primary-50 text-primary-700',
        'lost' => 'bg-rose-50 text-rose-700',
    ];
@endphp

<x-admin-layout :title="$lead->name">
    <div
        x-data="leadShow(@js(['baseUrl' => url('/admin/leads/'.$lead->id), 'indexUrl' => route('admin.leads.index')]))"
        x-cloak
        class="mx-auto max-w-4xl space-y-6"
    >
        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-ink">{{ $lead->name }}</h1>
                    @if ($lead->business_name)
                        <p class="text-sm text-slate-500">{{ $lead->business_name }}</p>
                    @endif
                    <div class="mt-2 flex flex-wrap items-center gap-2">
                        <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $statusColors[$lead->status] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ $lead->statusLabel() }}
                        </span>
                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">{{ $lead->sourceLabel() }}</span>
                        @if ($lead->isFollowUpOverdue())
                            <span class="rounded-full bg-rose-50 px-2 py-0.5 text-xs font-medium text-rose-700">Follow-up overdue</span>
                        @endif
                    </div>
                </div>

                <div class="flex gap-3">
                    @can('delete', $lead)
                        <button type="button" @click="confirmDelete()"
                                class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                            Delete
                        </button>
                    @endcan
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Email</p>
                <p class="mt-1 text-sm font-medium text-ink">{{ $lead->email }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Phone</p>
                <p class="mt-1 text-sm font-medium text-ink">{{ $lead->phone }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Service</p>
                <p class="mt-1 text-sm font-medium text-ink">{{ $lead->service ?: '—' }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Created</p>
                <p class="mt-1 text-sm font-medium text-ink">{{ $lead->created_at->format('M d, Y') }}</p>
            </div>
        </div>

        @can('update', $lead)
            <div class="rounded-2xl border border-slate-200 bg-white p-6">
                <h2 class="mb-4 text-base font-semibold text-ink">Pipeline</h2>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Status</label>
                        <select @change="changeStatus($event.target.value)"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                            @foreach (\App\Models\Lead::STATUSES as $value => $label)
                                <option value="{{ $value }}" @selected($lead->status === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Assigned To</label>
                        <select @change="changeAssignee($event.target.value)"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                            <option value="">Unassigned</option>
                            @foreach ($assignees as $assignee)
                                <option value="{{ $assignee->id }}" @selected($lead->assigned_to === $assignee->id)>{{ $assignee->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Follow-up Date</label>
                        <p class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-600">
                            {{ $lead->follow_up_date?->format('M d, Y') ?? 'Not set' }}
                        </p>
                    </div>
                </div>
            </div>
        @endcan

        @if ($lead->message)
            <div class="rounded-2xl border border-slate-200 bg-white p-6">
                <h2 class="mb-2 text-base font-semibold text-ink">Message</h2>
                <p class="whitespace-pre-line text-sm text-slate-600">{{ $lead->message }}</p>
            </div>
        @endif

        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="mb-4 text-base font-semibold text-ink">Lead Notes</h2>

            @can('update', $lead)
                <form @submit.prevent="addNote()" class="mb-5">
                    <textarea x-model="noteText" rows="3" placeholder="Add a note about this lead..."
                              class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200"></textarea>
                    <div class="mt-2 flex justify-end">
                        <button type="submit" :disabled="savingNote"
                                class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700 disabled:opacity-60">
                            <span x-show="!savingNote">Add Note</span>
                            <span x-show="savingNote">Saving...</span>
                        </button>
                    </div>
                </form>
            @endcan

            <div id="lead-notes-list" class="space-y-3">
                @forelse ($lead->notes as $note)
                    @include('admin.leads.partials.note', ['note' => $note])
                @empty
                    <p id="lead-notes-empty" class="text-sm text-slate-400">No notes yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Delete confirmation modal --}}
        <div x-show="deleteModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/50" @click="deleteModalOpen = false"></div>

            <div class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl">
                <h2 class="text-lg font-semibold text-ink">Delete lead?</h2>
                <p class="mt-2 text-sm text-slate-500">
                    Are you sure you want to delete <span class="font-medium text-ink">{{ $lead->name }}</span>? This cannot be undone.
                </p>

                <div class="mt-5 flex justify-end gap-3">
                    <button type="button" @click="deleteModalOpen = false" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">
                        Cancel
                    </button>
                    <button
                        type="button"
                        @click="deleteLead()"
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
