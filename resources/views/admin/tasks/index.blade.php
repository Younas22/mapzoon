<x-admin-layout title="Tasks">
    <div
        x-data="taskManager(@js(['storeUrl' => route('admin.tasks.store'), 'baseUrl' => url('/admin/tasks')]))"
        x-cloak
    >
        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <p class="text-sm font-medium text-slate-500">Total Tasks</p>
                <p class="mt-2 text-2xl font-bold text-ink">{{ $stats['total'] }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <p class="text-sm font-medium text-slate-500">In Progress</p>
                <p class="mt-2 text-2xl font-bold text-ink">{{ $stats['in_progress'] }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <p class="text-sm font-medium text-slate-500">Completed</p>
                <p class="mt-2 text-2xl font-bold text-primary-600">{{ $stats['completed'] }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <p class="text-sm font-medium text-slate-500">Overdue</p>
                <p class="mt-2 text-2xl font-bold text-rose-600">{{ $stats['overdue'] }}</p>
            </div>
        </div>

        <div class="mb-4 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                <input type="search" data-table-search placeholder="Search tasks..."
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm sm:w-64 focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">

                <select data-table-filter="status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm sm:w-40 focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                    <option value="">All Statuses</option>
                    @foreach (\App\Models\Task::STATUSES as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>

                <select data-table-filter="priority" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm sm:w-40 focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                    <option value="">All Priorities</option>
                    @foreach (\App\Models\Task::PRIORITIES as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>

                <select data-table-filter="assigned_to" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm sm:w-48 focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                    <option value="">All Assignees</option>
                    @foreach ($assignees as $assignee)
                        <option value="{{ $assignee->id }}">{{ $assignee->name }}</option>
                    @endforeach
                </select>
            </div>

            @can('create', App\Models\Task::class)
                <button type="button" @click="openCreate()"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">
                    + Add Task
                </button>
            @endcan
        </div>

        <div data-table data-table-url="{{ route('admin.tasks.data') }}">
            <div data-table-body>
                @include('admin.tasks.partials.table', ['tasks' => $tasks])
            </div>
        </div>

        {{-- Create / Edit modal --}}
        <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/50" @click="closeModal()"></div>

            <div class="relative max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white p-6 shadow-xl">
                <h2 class="text-lg font-semibold text-ink" x-text="mode === 'create' ? 'Add Task' : 'Edit Task'"></h2>

                <form @submit.prevent="submit()" class="mt-4 space-y-5">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Title</label>
                        <input type="text" x-model="form.title"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        <p class="mt-1 text-xs text-rose-600" x-show="errors.title" x-text="errors.title?.[0]"></p>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Description</label>
                        <textarea x-model="form.description" rows="3"
                                  class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200"></textarea>
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Priority</label>
                            <select x-model="form.priority" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                                @foreach (\App\Models\Task::PRIORITIES as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Status</label>
                            <select x-model="form.status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                                @foreach (\App\Models\Task::STATUSES as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Start Date</label>
                            <input type="date" x-model="form.start_date"
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Due Date</label>
                            <input type="date" x-model="form.due_date"
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                            <p class="mt-1 text-xs text-rose-600" x-show="errors.due_date" x-text="errors.due_date?.[0]"></p>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Assign To</label>
                            <select x-model="form.assigned_to" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                                <option value="">Unassigned</option>
                                @foreach ($assignees as $assignee)
                                    <option value="{{ $assignee->id }}">{{ $assignee->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Project</label>
                            <select x-model="form.project_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                                <option value="">No project</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
                        <button type="button" @click="closeModal()" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Cancel</button>
                        <button type="submit" :disabled="saving" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700 disabled:opacity-60">
                            <span x-show="!saving">Save Task</span>
                            <span x-show="saving">Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Delete confirmation modal --}}
        <div x-show="deleteModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/50" @click="deleteModalOpen = false"></div>

            <div class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl">
                <h2 class="text-lg font-semibold text-ink">Delete task?</h2>
                <p class="mt-2 text-sm text-slate-500">
                    Are you sure you want to delete <span class="font-medium text-ink" x-text="deleteTarget.name"></span>? This cannot be undone.
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
