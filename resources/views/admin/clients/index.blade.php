<x-admin-layout title="Clients">
    <div
        x-data="clientManager(@js(['storeUrl' => route('admin.clients.store'), 'baseUrl' => url('/admin/clients')]))"
        x-cloak
    >
        <div class="mb-4 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                <input type="search" data-table-search placeholder="Search clients..."
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm sm:w-64 focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">

                <select data-table-filter="status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm sm:w-40 focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                    <option value="">All Statuses</option>
                    @foreach (\App\Models\Client::STATUSES as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>

                <select data-table-filter="client_type" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm sm:w-40 focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                    <option value="">All Types</option>
                    @foreach (\App\Models\Client::CLIENT_TYPES as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            @can('create', App\Models\Client::class)
                <button type="button" @click="openCreate()"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">
                    + Add Client
                </button>
            @endcan
        </div>

        <div data-table data-table-url="{{ route('admin.clients.data') }}">
            <div data-table-body>
                @include('admin.clients.partials.table', ['clients' => $clients])
            </div>
        </div>

        {{-- Create / Edit modal --}}
        <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/50" @click="closeModal()"></div>

            <div class="relative max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white p-6 shadow-xl">
                <h2 class="text-lg font-semibold text-ink" x-text="mode === 'create' ? 'Add Client' : 'Edit Client'"></h2>

                <form @submit.prevent="submit()" class="mt-4 space-y-5">
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Company Name</label>
                            <input type="text" x-model="form.company_name"
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Owner Name</label>
                            <input type="text" x-model="form.owner_name"
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                            <p class="mt-1 text-xs text-rose-600" x-show="errors.owner_name" x-text="errors.owner_name?.[0]"></p>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Phone</label>
                            <input type="text" x-model="form.phone"
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                            <p class="mt-1 text-xs text-rose-600" x-show="errors.phone" x-text="errors.phone?.[0]"></p>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                            <input type="email" x-model="form.email"
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                            <p class="mt-1 text-xs text-rose-600" x-show="errors.email" x-text="errors.email?.[0]"></p>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Website</label>
                            <input type="text" x-model="form.website" placeholder="https://"
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Industry</label>
                            <input type="text" x-model="form.industry"
                                   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Client Type</label>
                            <select x-model="form.client_type" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                                @foreach (\App\Models\Client::CLIENT_TYPES as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Status</label>
                            <select x-model="form.status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                                @foreach (\App\Models\Client::STATUSES as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="mb-1 block text-sm font-medium text-slate-700">Address</label>
                            <textarea x-model="form.address" rows="2"
                                      class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200"></textarea>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="mb-1 block text-sm font-medium text-slate-700">Notes</label>
                            <textarea x-model="form.notes" rows="3"
                                      class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
                        <button type="button" @click="closeModal()" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Cancel</button>
                        <button type="submit" :disabled="saving" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700 disabled:opacity-60">
                            <span x-show="!saving">Save Client</span>
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
                <h2 class="text-lg font-semibold text-ink">Delete client?</h2>
                <p class="mt-2 text-sm text-slate-500">
                    Are you sure you want to delete <span class="font-medium text-ink" x-text="deleteTarget.name"></span>?
                    This also removes their contacts, files, contracts, and invoices. This cannot be undone.
                </p>

                <div class="mt-5 flex justify-end gap-3">
                    <button type="button" @click="deleteModalOpen = false" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Cancel</button>
                    <button type="button" @click="deleteClient()" :disabled="deleting"
                            class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700 disabled:opacity-60">
                        <span x-show="!deleting">Delete</span>
                        <span x-show="deleting">Deleting...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
