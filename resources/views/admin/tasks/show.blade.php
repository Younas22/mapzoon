@php
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

    $canManage = auth()->user()->can('updateProgress', $task);
    $canEdit = auth()->user()->can('update', $task);
@endphp

<x-admin-layout :title="$task->title">
    <div
        x-data="taskShow(@js(['baseUrl' => url('/admin/tasks/'.$task->id), 'indexUrl' => route('admin.tasks.index')]))"
        x-cloak
        class="mx-auto max-w-5xl space-y-6"
    >
        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-ink">{{ $task->title }}</h1>
                    <div class="mt-2 flex flex-wrap items-center gap-2">
                        <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $statusColors[$task->status] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ $task->statusLabel() }}
                        </span>
                        <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $priorityColors[$task->priority] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ $task->priorityLabel() }} Priority
                        </span>
                        @if ($task->isOverdue())
                            <span class="rounded-full bg-rose-50 px-2 py-0.5 text-xs font-medium text-rose-700">Overdue</span>
                        @endif
                    </div>
                </div>

                <div class="flex gap-3">
                    @if ($canEdit)
                        @can('delete', $task)
                            <button type="button" @click="confirmDelete()"
                                    class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                                Delete
                            </button>
                        @endcan
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                @if ($task->description)
                    <div class="rounded-2xl border border-slate-200 bg-white p-6">
                        <h2 class="mb-2 text-base font-semibold text-ink">Description</h2>
                        <p class="whitespace-pre-line text-sm leading-relaxed text-slate-600">{{ $task->description }}</p>
                    </div>
                @endif

                <div class="rounded-2xl border border-slate-200 bg-white p-6">
                    <h2 class="mb-4 text-base font-semibold text-ink">Subtasks</h2>

                    <ul id="task-subtasks-list" class="space-y-2">
                        @forelse ($task->subtasks as $subtask)
                            @include('admin.tasks.partials.subtask', ['subtask' => $subtask])
                        @empty
                            <p id="task-subtasks-empty" class="text-sm text-slate-400">No subtasks yet.</p>
                        @endforelse
                    </ul>

                    <form @submit.prevent="addSubtask()" class="mt-4 flex gap-2">
                        <input type="text" x-model="subtaskTitle" placeholder="Add a subtask..."
                               class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        <button type="submit" :disabled="savingSubtask" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700 disabled:opacity-60">
                            Add
                        </button>
                    </form>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6">
                    <h2 class="mb-4 text-base font-semibold text-ink">Comments</h2>

                    <form @submit.prevent="addComment()" class="mb-4">
                        <textarea x-model="commentText" rows="2" placeholder="Write a comment..."
                                  class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200"></textarea>
                        <div class="mt-2 flex justify-end">
                            <button type="submit" :disabled="savingComment" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700 disabled:opacity-60">
                                <span x-show="!savingComment">Comment</span>
                                <span x-show="savingComment">Posting...</span>
                            </button>
                        </div>
                    </form>

                    <div id="task-comments-list" class="space-y-3">
                        @forelse ($task->comments as $comment)
                            @include('admin.tasks.partials.comment', ['comment' => $comment])
                        @empty
                            <p id="task-comments-empty" class="text-sm text-slate-400">No comments yet.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6">
                    <h2 class="mb-1 text-base font-semibold text-ink">Notes</h2>
                    <p class="mb-4 text-sm text-slate-500">Internal notes about this task.</p>

                    <form @submit.prevent="addNote()" class="mb-4">
                        <textarea x-model="noteText" rows="2" placeholder="Add an internal note..."
                                  class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200"></textarea>
                        <div class="mt-2 flex justify-end">
                            <button type="submit" :disabled="savingNote" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700 disabled:opacity-60">
                                <span x-show="!savingNote">Add Note</span>
                                <span x-show="savingNote">Saving...</span>
                            </button>
                        </div>
                    </form>

                    <div id="task-notes-list" class="space-y-3">
                        @forelse ($task->notes as $note)
                            @include('admin.tasks.partials.note', ['note' => $note])
                        @empty
                            <p id="task-notes-empty" class="text-sm text-slate-400">No notes yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-6">
                    <h2 class="mb-4 text-base font-semibold text-ink">Details</h2>

                    <div class="space-y-4 text-sm">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Status</p>
                            @if ($canManage)
                                <select @change="changeStatus($event.target.value)" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                                    @foreach (\App\Models\Task::STATUSES as $value => $label)
                                        <option value="{{ $value }}" @selected($task->status === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            @else
                                <p class="mt-1 font-medium text-ink">{{ $task->statusLabel() }}</p>
                            @endif
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Progress</p>
                            @if ($canManage)
                                <div class="mt-1 flex items-center gap-2">
                                    <input type="range" min="0" max="100" step="5" value="{{ $task->progress }}"
                                           @change="changeProgress($event.target.value)"
                                           class="flex-1 accent-primary-600">
                                    <span class="w-10 text-right font-medium text-ink">{{ $task->progress }}%</span>
                                </div>
                            @else
                                <div class="mt-1 flex items-center gap-2">
                                    <div class="h-1.5 flex-1 overflow-hidden rounded-full bg-slate-100">
                                        <div class="h-full bg-primary-500" style="width: {{ $task->progress }}%"></div>
                                    </div>
                                    <span class="text-xs text-slate-500">{{ $task->progress }}%</span>
                                </div>
                            @endif
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Assigned To</p>
                            @if ($canEdit)
                                <select @change="changeAssignee($event.target.value)" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                                    <option value="">Unassigned</option>
                                    @foreach ($assignees as $assignee)
                                        <option value="{{ $assignee->id }}" @selected($task->assigned_to === $assignee->id)>{{ $assignee->name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <p class="mt-1 font-medium text-ink">{{ $task->assignedUser?->name ?? 'Unassigned' }}</p>
                            @endif
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Start Date</p>
                            <p class="mt-1 font-medium text-ink">{{ $task->start_date?->format('M d, Y') ?? 'Not set' }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Due Date</p>
                            <p class="mt-1 font-medium {{ $task->isOverdue() ? 'text-rose-600' : 'text-ink' }}">{{ $task->due_date?->format('M d, Y') ?? 'Not set' }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Created By</p>
                            <p class="mt-1 font-medium text-ink">{{ $task->creator?->name ?? 'Unknown' }} on {{ $task->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6">
                    <h2 class="mb-4 text-base font-semibold text-ink">Attachments</h2>

                    <div id="task-attachments-list" class="mb-4 space-y-2">
                        @forelse ($task->attachments as $attachment)
                            @include('admin.tasks.partials.attachment', ['task' => $task, 'attachment' => $attachment])
                        @empty
                            <p id="task-attachments-empty" class="text-sm text-slate-400">No attachments yet.</p>
                        @endforelse
                    </div>

                    <label class="block">
                        <span class="sr-only">Upload attachment</span>
                        <input type="file" @change="uploadAttachment($event)" :disabled="uploadingAttachment" class="block w-full text-sm text-slate-600">
                    </label>
                    <p class="mt-1 text-xs text-slate-400">Up to 10MB per file.</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6">
                    <h2 class="mb-4 text-base font-semibold text-ink">Activity Log</h2>

                    <ol class="space-y-3">
                        @forelse ($task->activities as $activity)
                            <li class="border-l-2 border-slate-100 pl-3">
                                <p class="text-sm text-slate-600">{{ $activity->description }}</p>
                                <p class="text-xs text-slate-400">{{ $activity->created_at->diffForHumans() }}</p>
                            </li>
                        @empty
                            <p class="text-sm text-slate-400">No activity yet.</p>
                        @endforelse
                    </ol>
                </div>
            </div>
        </div>

        {{-- Delete confirmation modal --}}
        <div x-show="deleteModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/50" @click="deleteModalOpen = false"></div>

            <div class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl">
                <h2 class="text-lg font-semibold text-ink">Delete task?</h2>
                <p class="mt-2 text-sm text-slate-500">
                    Are you sure you want to delete <span class="font-medium text-ink">{{ $task->title }}</span>? This cannot be undone.
                </p>

                <div class="mt-5 flex justify-end gap-3">
                    <button type="button" @click="deleteModalOpen = false" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Cancel</button>
                    <button type="button" @click="deleteTask()" :disabled="deleting"
                            class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700 disabled:opacity-60">
                        <span x-show="!deleting">Delete</span>
                        <span x-show="deleting">Deleting...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
