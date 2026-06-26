@php
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

    $canEdit = auth()->user()->can('update', $project);
    $canViewCredentials = auth()->user()->can('viewAny', App\Models\ProjectCredential::class);
@endphp

<x-admin-layout :title="$project->name">
    <div
        x-data="projectShow(@js(['baseUrl' => url('/admin/projects/'.$project->id), 'indexUrl' => route('admin.projects.index'), 'teamMemberIds' => $project->teamMembers->pluck('id')->all()]))"
        x-cloak
        class="mx-auto max-w-5xl space-y-6"
    >
        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-ink">{{ $project->name }}</h1>
                    <p class="text-sm text-slate-500">
                        <a href="{{ route('admin.clients.show', $project->client) }}" class="hover:underline">{{ $project->client->displayName() }}</a>
                        @if ($project->project_type)
                            · {{ $project->project_type }}
                        @endif
                    </p>
                    <div class="mt-2 flex flex-wrap items-center gap-2">
                        <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $statusColors[$project->status] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ $project->statusLabel() }}
                        </span>
                        <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $priorityColors[$project->priority] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ $project->priorityLabel() }} Priority
                        </span>
                        @if ($project->isOverdue())
                            <span class="rounded-full bg-rose-50 px-2 py-0.5 text-xs font-medium text-rose-700">Overdue</span>
                        @endif
                    </div>
                </div>

                @can('delete', $project)
                    <button type="button" @click="confirmDelete()" class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                        Delete
                    </button>
                @endcan
            </div>

            <div class="mt-5 flex items-center gap-3">
                @if ($canEdit)
                    <input type="range" min="0" max="100" step="5" value="{{ $project->progress }}"
                           @change="changeProgress($event.target.value)" class="flex-1 max-w-xs accent-primary-600">
                @else
                    <div class="h-1.5 max-w-xs flex-1 overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full bg-primary-500" style="width: {{ $project->progress }}%"></div>
                    </div>
                @endif
                <span class="text-sm font-medium text-ink">{{ $project->progress }}% complete</span>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="flex flex-wrap gap-2 border-b border-slate-200">
            @php
                $tabs = ['overview' => 'Overview', 'team' => 'Team', 'timeline' => 'Timeline', 'tasks' => 'Tasks', 'files' => 'Documents', 'discussion' => 'Discussion'];
                if ($canEdit) {
                    $tabs += ['notes' => 'Notes', 'activity' => 'Activity Log'];
                }
                if ($canViewCredentials) {
                    $tabs += ['credentials' => 'Credentials'];
                }
            @endphp
            @foreach ($tabs as $key => $label)
                <button
                    type="button"
                    @click="activeTab = '{{ $key }}'"
                    :class="activeTab === '{{ $key }}' ? 'border-primary-600 text-primary-700' : 'border-transparent text-slate-500 hover:text-ink'"
                    class="-mb-px border-b-2 px-3 py-2 text-sm font-medium"
                >
                    {{ $label }}
                </button>
            @endforeach
        </div>

        {{-- Overview --}}
        <div x-show="activeTab === 'overview'" class="rounded-2xl border border-slate-200 bg-white p-6">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Budget</p>
                    <p class="mt-1 font-medium text-ink">{{ $project->budget ? '$'.number_format($project->budget, 2) : 'Not set' }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Timeline</p>
                    <p class="mt-1 font-medium text-ink">{{ $project->start_date?->format('M d, Y') ?? '—' }} &rarr; {{ $project->end_date?->format('M d, Y') ?? '—' }}</p>
                </div>
                <div class="sm:col-span-2">
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Services Included</p>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @forelse ($project->services_included ?? [] as $service)
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700">{{ $service }}</span>
                        @empty
                            <span class="text-sm text-slate-400">None specified.</span>
                        @endforelse
                    </div>
                </div>
                @if ($project->description)
                    <div class="sm:col-span-2">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Description</p>
                        <p class="mt-1 whitespace-pre-line text-sm text-slate-600">{{ $project->description }}</p>
                    </div>
                @endif
            </div>

            <p class="mt-4 text-xs text-slate-400">Created by {{ $project->creator?->name ?? 'Unknown' }} on {{ $project->created_at->format('M d, Y') }}</p>
        </div>

        {{-- Team --}}
        <div x-show="activeTab === 'team'" class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="mb-4 text-base font-semibold text-ink">Assigned Team Members</h2>

            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                @foreach ($users as $user)
                    <label class="flex items-center gap-2 text-sm text-slate-600">
                        <input type="checkbox" value="{{ $user->id }}" x-model="teamSelected" class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                        {{ $user->name }}
                    </label>
                @endforeach
            </div>

            @if ($canEdit)
                <div class="mt-5 flex justify-end border-t border-slate-100 pt-4">
                    <button type="button" @click="saveTeam()" :disabled="savingTeam" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700 disabled:opacity-60">
                        <span x-show="!savingTeam">Save Team</span>
                        <span x-show="savingTeam">Saving...</span>
                    </button>
                </div>
            @endif
        </div>

        {{-- Timeline / Milestones --}}
        <div x-show="activeTab === 'timeline'" class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="mb-4 text-base font-semibold text-ink">Project Timeline</h2>

            <ul id="project-milestones-list" class="space-y-2">
                @forelse ($project->milestones as $milestone)
                    @include('admin.projects.partials.milestone', ['milestone' => $milestone])
                @empty
                    <p id="project-milestones-empty" class="text-sm text-slate-400">No milestones added yet.</p>
                @endforelse
            </ul>

            @if ($canEdit)
                <form @submit.prevent="addMilestone()" class="mt-4 flex flex-wrap gap-2">
                    <input type="text" x-model="milestoneForm.title" placeholder="Milestone title"
                           class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                    <input type="date" x-model="milestoneForm.date"
                           class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                    <button type="submit" :disabled="savingMilestone" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700 disabled:opacity-60">
                        Add
                    </button>
                </form>
            @endif
        </div>

        {{-- Tasks --}}
        <div x-show="activeTab === 'tasks'" class="rounded-2xl border border-slate-200 bg-white p-6">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-base font-semibold text-ink">Tasks</h2>
                @can('viewAny', App\Models\Task::class)
                    <a href="{{ route('admin.tasks.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">View all tasks &rarr;</a>
                @endcan
            </div>

            <div class="space-y-2">
                @forelse ($project->tasks as $task)
                    <a href="{{ route('admin.tasks.show', $task) }}" class="flex items-center justify-between rounded-lg border border-slate-100 px-3 py-2 hover:border-primary-200">
                        <span class="text-sm text-ink">{{ $task->title }}</span>
                        <span class="text-xs text-slate-500">{{ $task->statusLabel() }}</span>
                    </a>
                @empty
                    <p class="text-sm text-slate-400">No tasks linked to this project yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Documents --}}
        <div x-show="activeTab === 'files'" class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="mb-4 text-base font-semibold text-ink">Project Documents</h2>

            <div id="project-files-list" class="mb-4 space-y-2">
                @forelse ($project->files as $projectFile)
                    @include('admin.projects.partials.file', ['project' => $project, 'projectFile' => $projectFile])
                @empty
                    <p id="project-files-empty" class="text-sm text-slate-400">No documents uploaded yet.</p>
                @endforelse
            </div>

            @if ($canEdit)
                <label class="block">
                    <span class="sr-only">Upload document</span>
                    <input type="file" @change="uploadFile($event)" :disabled="uploadingFile" class="block w-full text-sm text-slate-600">
                </label>
                <p class="mt-1 text-xs text-slate-400">Up to 10MB per file.</p>
            @endif
        </div>

        {{-- Discussion --}}
        <div x-show="activeTab === 'discussion'" class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="mb-4 text-base font-semibold text-ink">Project Discussion</h2>

            <form @submit.prevent="addDiscussion()" class="mb-4">
                <textarea x-model="messageText" rows="2" placeholder="Post a message..."
                          class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200"></textarea>
                <div class="mt-2 flex justify-end">
                    <button type="submit" :disabled="savingMessage" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700 disabled:opacity-60">
                        <span x-show="!savingMessage">Post</span>
                        <span x-show="savingMessage">Posting...</span>
                    </button>
                </div>
            </form>

            <div id="project-discussions-list" class="space-y-3">
                @forelse ($project->discussions as $discussion)
                    @include('admin.projects.partials.discussion', ['discussion' => $discussion])
                @empty
                    <p id="project-discussions-empty" class="text-sm text-slate-400">No messages yet.</p>
                @endforelse
            </div>
        </div>

        @if ($canEdit)
            {{-- Notes --}}
            <div x-show="activeTab === 'notes'" class="rounded-2xl border border-slate-200 bg-white p-6">
                <h2 class="mb-1 text-base font-semibold text-ink">Internal Notes</h2>
                <p class="mb-4 text-sm text-slate-500">Visible to the team only.</p>

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

                <div id="project-notes-list" class="space-y-3">
                    @forelse ($project->notes as $note)
                        @include('admin.projects.partials.note', ['note' => $note])
                    @empty
                        <p id="project-notes-empty" class="text-sm text-slate-400">No notes yet.</p>
                    @endforelse
                </div>
            </div>

            {{-- Activity Log --}}
            <div x-show="activeTab === 'activity'" class="rounded-2xl border border-slate-200 bg-white p-6">
                <h2 class="mb-4 text-base font-semibold text-ink">Activity Log</h2>

                <ol class="space-y-3">
                    @forelse ($project->activities as $activity)
                        <li class="border-l-2 border-slate-100 pl-3">
                            <p class="text-sm text-slate-600">{{ $activity->description }}</p>
                            <p class="text-xs text-slate-400">{{ $activity->created_at->diffForHumans() }}</p>
                        </li>
                    @empty
                        <p class="text-sm text-slate-400">No activity yet.</p>
                    @endforelse
                </ol>
            </div>
        @endif

        @if ($canViewCredentials)
            {{-- Credentials --}}
            <div x-show="activeTab === 'credentials'" class="rounded-2xl border border-slate-200 bg-white p-6">
                <div class="mb-1 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-ink">Project Credentials</h2>
                    @can('create', App\Models\ProjectCredential::class)
                        <button type="button" @click="openCreateCredential()" class="rounded-lg bg-primary-600 px-3 py-1.5 text-sm font-semibold text-white hover:bg-primary-700">
                            + Add Credential
                        </button>
                    @endcan
                </div>
                <p class="mb-4 text-sm text-slate-500">Visible to permitted team members only. Stored encrypted at rest. Every password reveal is logged.</p>

                <div id="project-credentials-list" class="space-y-3">
                    @forelse ($project->credentials as $credential)
                        @include('admin.projects.partials.credential', ['project' => $project, 'credential' => $credential])
                    @empty
                        <p id="project-credentials-empty" class="text-sm text-slate-400">No credentials saved yet.</p>
                    @endforelse
                </div>
            </div>
        @endif

        @if ($canViewCredentials)
        {{-- Credential modal --}}
        <div x-show="credentialModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/50" @click="credentialModalOpen = false"></div>
            <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
                <h2 class="text-lg font-semibold text-ink" x-text="credentialForm.id ? 'Edit Credential' : 'Add Credential'"></h2>
                <form @submit.prevent="submitCredential()" class="mt-4 space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Platform</label>
                            <select x-model="credentialForm.platform" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                                @foreach (\App\Models\ProjectCredential::PLATFORMS as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Label</label>
                            <input type="text" x-model="credentialForm.label" placeholder="e.g. Client's Gmail"
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                            <p class="mt-1 text-xs text-rose-600" x-show="credentialErrors.label" x-text="credentialErrors.label?.[0]"></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Username</label>
                            <input type="text" x-model="credentialForm.username" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">
                                Password
                                <button type="button" x-show="credentialForm.id" @click="revealPasswordIntoForm()" class="ml-1 text-xs font-medium text-primary-600 hover:text-primary-700">(reveal current)</button>
                            </label>
                            <input type="text" x-model="credentialForm.password" placeholder="Leave blank to keep current"
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Recovery Email</label>
                            <input type="email" x-model="credentialForm.recovery_email" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Recovery Phone</label>
                            <input type="text" x-model="credentialForm.recovery_phone" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">URL</label>
                        <input type="text" x-model="credentialForm.url" placeholder="https://" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Notes</label>
                        <textarea x-model="credentialForm.notes" rows="2" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
                        <button type="button" @click="credentialModalOpen = false" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Cancel</button>
                        <button type="submit" :disabled="savingCredential" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700 disabled:opacity-60">Save</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Credential history modal --}}
        <div x-show="historyModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/50" @click="historyModalOpen = false"></div>
            <div class="relative max-h-[80vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white p-6 shadow-xl">
                <h2 class="text-lg font-semibold text-ink">Credential History</h2>
                <p class="mt-1 text-sm text-slate-500">Every change to this credential, including who made it and when.</p>

                <div class="mt-4 space-y-3">
                    <p x-show="loadingHistory" class="text-sm text-slate-400">Loading...</p>
                    <template x-for="entry in historyEntries" :key="entry.id">
                        <div class="rounded-xl border border-slate-200 p-3">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-sm font-medium text-ink" x-text="entry.label"></p>
                                    <p class="text-xs text-slate-400" x-text="entry.platform_label + ' · ' + entry.action + ' by ' + entry.changed_by"></p>
                                </div>
                                <span class="text-xs text-slate-400" x-text="entry.created_at"></span>
                            </div>
                            <div class="mt-2 flex items-center gap-2 text-sm text-slate-600" x-show="entry.has_password">
                                <span class="text-xs text-slate-400">Password at the time:</span>
                                <span x-show="! isHistoryRevealed(entry.id)">••••••••</span>
                                <span x-show="isHistoryRevealed(entry.id)" x-cloak x-text="revealedHistoryPassword(entry.id)"></span>
                                <button type="button" @click="toggleHistoryReveal(entry.id)" :disabled="revealingHistoryId === entry.id" class="text-xs font-medium text-primary-600 hover:text-primary-700 disabled:opacity-50">
                                    <span x-show="! isHistoryRevealed(entry.id)">Show</span>
                                    <span x-show="isHistoryRevealed(entry.id)" x-cloak>Hide</span>
                                </button>
                            </div>
                        </div>
                    </template>
                    <p x-show="! loadingHistory && historyEntries.length === 0" class="text-sm text-slate-400">No history yet.</p>
                </div>

                <div class="mt-4 flex justify-end border-t border-slate-100 pt-4">
                    <button type="button" @click="historyModalOpen = false" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Close</button>
                </div>
            </div>
        </div>
        @endif

        {{-- Delete confirmation modal --}}
        <div x-show="deleteModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/50" @click="deleteModalOpen = false"></div>
            <div class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl">
                <h2 class="text-lg font-semibold text-ink">Delete project?</h2>
                <p class="mt-2 text-sm text-slate-500">
                    Are you sure you want to delete <span class="font-medium text-ink">{{ $project->name }}</span>?
                    This also removes its files, notes, discussion, credentials, and milestones. This cannot be undone.
                </p>
                <div class="mt-5 flex justify-end gap-3">
                    <button type="button" @click="deleteModalOpen = false" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Cancel</button>
                    <button type="button" @click="deleteProject()" :disabled="deleting"
                            class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700 disabled:opacity-60">
                        <span x-show="!deleting">Delete</span>
                        <span x-show="deleting">Deleting...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
