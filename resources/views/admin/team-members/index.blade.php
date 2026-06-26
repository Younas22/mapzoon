<x-admin-layout title="Team">
    <div
        x-data="teamMemberManager(@js(['storeUrl' => route('admin.team-members.store'), 'baseUrl' => url('/admin/team-members')]))"
        x-cloak
    >
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                <input type="search" data-table-search placeholder="Search team members..."
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm sm:w-64 focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">

                <select data-table-filter="status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm sm:w-40 focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                    <option value="">All Statuses</option>
                    @foreach (\App\Models\TeamMember::STATUSES as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>

                <select data-table-filter="visible" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm sm:w-44 focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                    <option value="">All (Homepage)</option>
                    <option value="1">Visible on Homepage</option>
                    <option value="0">Hidden from Homepage</option>
                </select>
            </div>

            @can('create', App\Models\TeamMember::class)
                <button type="button" @click="openCreate()"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">
                    + Add Team Member
                </button>
            @endcan
        </div>

        <div data-table data-table-url="{{ route('admin.team-members.data') }}">
            <div data-table-body>
                @include('admin.team-members.partials.table', ['teamMembers' => $teamMembers])
            </div>
        </div>

        {{-- Create / Edit modal --}}
        <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/50" @click="closeModal()"></div>

            <div class="relative max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white p-6 shadow-xl">
                <h2 class="text-lg font-semibold text-ink" x-text="mode === 'create' ? 'Add Team Member' : 'Edit Team Member'"></h2>

                <form @submit.prevent="submit()" class="mt-4 space-y-5">
                    <div class="flex items-center gap-4">
                        <div class="flex h-16 w-16 flex-none items-center justify-center overflow-hidden rounded-full bg-slate-100">
                            <img x-show="photoPreview" :src="photoPreview" class="h-full w-full object-cover" alt="">
                            <svg x-show="!photoPreview" class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Photo</label>
                            <input type="file" accept="image/*" @change="onPhotoChange($event)" class="mt-1 block text-sm text-slate-600">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Name</label>
                            <input type="text" x-model="form.name"
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                            <p class="mt-1 text-xs text-rose-600" x-show="errors.name" x-text="errors.name?.[0]"></p>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Designation</label>
                            <input type="text" x-model="form.designation"
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                            <p class="mt-1 text-xs text-rose-600" x-show="errors.designation" x-text="errors.designation?.[0]"></p>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                            <input type="email" x-model="form.email"
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">LinkedIn URL</label>
                            <input type="text" x-model="form.linkedin_url" placeholder="https://linkedin.com/in/..."
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Display Order</label>
                            <input type="number" min="0" x-model="form.display_order"
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Status</label>
                            <select x-model="form.status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                                @foreach (\App\Models\TeamMember::STATUSES as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="flex items-center gap-2 text-sm text-slate-600">
                                <input type="checkbox" x-model="form.is_visible_on_homepage" class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                                Show on homepage Team section
                            </label>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="mb-1 block text-sm font-medium text-slate-700">Bio</label>
                            <textarea x-model="form.bio" rows="3"
                                      class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
                        <button type="button" @click="closeModal()" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Cancel</button>
                        <button type="submit" :disabled="saving" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700 disabled:opacity-60">
                            <span x-show="!saving">Save</span>
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
                <h2 class="text-lg font-semibold text-ink">Delete team member?</h2>
                <p class="mt-2 text-sm text-slate-500">
                    Are you sure you want to delete <span class="font-medium text-ink" x-text="deleteTarget.name"></span>? This cannot be undone.
                </p>

                <div class="mt-5 flex justify-end gap-3">
                    <button type="button" @click="deleteModalOpen = false" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Cancel</button>
                    <button type="button" @click="deleteTeamMember()" :disabled="deleting"
                            class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700 disabled:opacity-60">
                        <span x-show="!deleting">Delete</span>
                        <span x-show="deleting">Deleting...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
