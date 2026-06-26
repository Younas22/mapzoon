@php
    $statusColors = [
        'active' => 'bg-primary-50 text-primary-700',
        'inactive' => 'bg-slate-100 text-slate-600',
        'on_hold' => 'bg-amber-50 text-amber-700',
    ];

    $clientData = $client->only([
        'company_name', 'owner_name', 'phone', 'email', 'website',
        'address', 'industry', 'notes', 'status', 'client_type',
    ]);

    $canEdit = auth()->user()->can('update', $client);
@endphp

<x-admin-layout :title="$client->displayName()">
    <div
        x-data="clientShow(@js(['baseUrl' => url('/admin/clients/'.$client->id), 'indexUrl' => route('admin.clients.index'), 'client' => $clientData, 'teamMemberIds' => $client->teamMembers->pluck('id')->all()]))"
        x-cloak
        class="mx-auto max-w-5xl space-y-6"
    >
        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-ink">{{ $client->displayName() }}</h1>
                    <p class="text-sm text-slate-500">{{ $client->email }} · {{ $client->phone }}</p>
                    <div class="mt-2 flex flex-wrap items-center gap-2">
                        <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $statusColors[$client->status] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ $client->statusLabel() }}
                        </span>
                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">{{ $client->clientTypeLabel() }}</span>
                    </div>
                </div>

                @can('delete', $client)
                    <button type="button" @click="confirmDelete()" class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                        Delete
                    </button>
                @endcan
            </div>
        </div>

        {{-- Tabs --}}
        <div class="flex flex-wrap gap-2 border-b border-slate-200">
            @foreach (['overview' => 'Overview', 'contacts' => 'Contacts', 'files' => 'Files', 'contracts' => 'Contracts', 'invoices' => 'Invoices', 'projects' => 'Projects', 'team' => 'Team'] as $key => $label)
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
            <form @submit.prevent="saveOverview()" class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Company Name</label>
                    <input type="text" x-model="overviewForm.company_name" @disabled(! $canEdit)
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200 disabled:bg-slate-50">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Owner Name</label>
                    <input type="text" x-model="overviewForm.owner_name" @disabled(! $canEdit)
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200 disabled:bg-slate-50">
                    <p class="mt-1 text-xs text-rose-600" x-show="overviewErrors.owner_name" x-text="overviewErrors.owner_name?.[0]"></p>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Phone</label>
                    <input type="text" x-model="overviewForm.phone" @disabled(! $canEdit)
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200 disabled:bg-slate-50">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                    <input type="email" x-model="overviewForm.email" @disabled(! $canEdit)
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200 disabled:bg-slate-50">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Website</label>
                    <input type="text" x-model="overviewForm.website" @disabled(! $canEdit)
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200 disabled:bg-slate-50">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Industry</label>
                    <input type="text" x-model="overviewForm.industry" @disabled(! $canEdit)
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200 disabled:bg-slate-50">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Client Type</label>
                    <select x-model="overviewForm.client_type" @disabled(! $canEdit)
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200 disabled:bg-slate-50">
                        @foreach (\App\Models\Client::CLIENT_TYPES as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Status</label>
                    <select x-model="overviewForm.status" @disabled(! $canEdit)
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200 disabled:bg-slate-50">
                        @foreach (\App\Models\Client::STATUSES as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Address</label>
                    <textarea x-model="overviewForm.address" rows="2" @disabled(! $canEdit)
                              class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200 disabled:bg-slate-50"></textarea>
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Notes</label>
                    <textarea x-model="overviewForm.notes" rows="3" @disabled(! $canEdit)
                              class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200 disabled:bg-slate-50"></textarea>
                </div>

                @if ($canEdit)
                    <div class="sm:col-span-2 flex justify-end border-t border-slate-100 pt-4">
                        <button type="submit" :disabled="savingOverview" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700 disabled:opacity-60">
                            <span x-show="!savingOverview">Save Changes</span>
                            <span x-show="savingOverview">Saving...</span>
                        </button>
                    </div>
                @endif
            </form>

            <p class="mt-4 text-xs text-slate-400">Added by {{ $client->creator?->name ?? 'Unknown' }} on {{ $client->created_at->format('M d, Y') }}</p>
        </div>

        {{-- Contacts --}}
        <div x-show="activeTab === 'contacts'" class="rounded-2xl border border-slate-200 bg-white p-6">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-base font-semibold text-ink">Contacts</h2>
                @can('update', $client)
                    <button type="button" @click="openCreateContact()" class="rounded-lg bg-primary-600 px-3 py-1.5 text-sm font-semibold text-white hover:bg-primary-700">
                        + Add Contact
                    </button>
                @endcan
            </div>

            <div id="client-contacts-list" class="space-y-3">
                @forelse ($client->contacts as $contact)
                    @include('admin.clients.partials.contact', ['client' => $client, 'contact' => $contact])
                @empty
                    <p id="client-contacts-empty" class="text-sm text-slate-400">No contacts added yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Files --}}
        <div x-show="activeTab === 'files'" class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="mb-4 text-base font-semibold text-ink">Files</h2>

            <div id="client-files-list" class="mb-4 space-y-2">
                @forelse ($client->files as $clientFile)
                    @include('admin.clients.partials.file', ['client' => $client, 'clientFile' => $clientFile])
                @empty
                    <p id="client-files-empty" class="text-sm text-slate-400">No files uploaded yet.</p>
                @endforelse
            </div>

            @can('update', $client)
                <label class="block">
                    <span class="sr-only">Upload file</span>
                    <input type="file" @change="uploadFile($event)" :disabled="uploadingFile" class="block w-full text-sm text-slate-600">
                </label>
                <p class="mt-1 text-xs text-slate-400">Up to 10MB per file.</p>
            @endcan
        </div>

        {{-- Contracts --}}
        <div x-show="activeTab === 'contracts'" class="rounded-2xl border border-slate-200 bg-white p-6">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-base font-semibold text-ink">Contracts</h2>
                @can('update', $client)
                    <button type="button" @click="openCreateContract()" class="rounded-lg bg-primary-600 px-3 py-1.5 text-sm font-semibold text-white hover:bg-primary-700">
                        + Add Contract
                    </button>
                @endcan
            </div>

            <div id="client-contracts-list" class="space-y-3">
                @forelse ($client->contracts as $contract)
                    @include('admin.clients.partials.contract', ['client' => $client, 'contract' => $contract])
                @empty
                    <p id="client-contracts-empty" class="text-sm text-slate-400">No contracts yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Invoices --}}
        <div x-show="activeTab === 'invoices'" class="rounded-2xl border border-slate-200 bg-white p-6">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-base font-semibold text-ink">Invoices</h2>
                @can('update', $client)
                    <button type="button" @click="openCreateInvoice()" class="rounded-lg bg-primary-600 px-3 py-1.5 text-sm font-semibold text-white hover:bg-primary-700">
                        + Add Invoice
                    </button>
                @endcan
            </div>

            <div id="client-invoices-list" class="space-y-3">
                @forelse ($client->invoices as $invoice)
                    @include('admin.clients.partials.invoice', ['client' => $client, 'invoice' => $invoice])
                @empty
                    <p id="client-invoices-empty" class="text-sm text-slate-400">No invoices yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Projects --}}
        <div x-show="activeTab === 'projects'" class="rounded-2xl border border-slate-200 bg-white p-6">
            @php
                $projectStatusColors = [
                    'planning' => 'bg-slate-100 text-slate-600',
                    'in_progress' => 'bg-blue-50 text-blue-700',
                    'on_hold' => 'bg-amber-50 text-amber-700',
                    'completed' => 'bg-primary-50 text-primary-700',
                    'cancelled' => 'bg-rose-50 text-rose-700',
                ];
            @endphp

            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-base font-semibold text-ink">Projects</h2>
                @can('create', App\Models\Project::class)
                    <a href="{{ route('admin.projects.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">+ Add Project &rarr;</a>
                @endcan
            </div>

            <div class="space-y-2">
                @forelse ($client->projects as $project)
                    <a href="{{ route('admin.projects.show', $project) }}" class="flex items-center justify-between gap-3 rounded-lg border border-slate-100 px-3 py-2 hover:border-primary-200">
                        <div>
                            <span class="block text-sm font-medium text-ink">{{ $project->name }}</span>
                            <span class="block text-xs text-slate-500">{{ $project->project_type ?: '—' }}</span>
                        </div>
                        <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $projectStatusColors[$project->status] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ $project->statusLabel() }}
                        </span>
                    </a>
                @empty
                    <p class="text-sm text-slate-400">No projects linked to this client yet.</p>
                @endforelse
            </div>
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

            @can('update', $client)
                <div class="mt-5 flex justify-end border-t border-slate-100 pt-4">
                    <button type="button" @click="saveTeam()" :disabled="savingTeam" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700 disabled:opacity-60">
                        <span x-show="!savingTeam">Save Team</span>
                        <span x-show="savingTeam">Saving...</span>
                    </button>
                </div>
            @endcan
        </div>

        {{-- Contact modal --}}
        <div x-show="contactModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/50" @click="contactModalOpen = false"></div>
            <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
                <h2 class="text-lg font-semibold text-ink" x-text="contactForm.id ? 'Edit Contact' : 'Add Contact'"></h2>
                <form @submit.prevent="submitContact()" class="mt-4 space-y-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Name</label>
                        <input type="text" x-model="contactForm.name" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        <p class="mt-1 text-xs text-rose-600" x-show="contactErrors.name" x-text="contactErrors.name?.[0]"></p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Designation</label>
                        <input type="text" x-model="contactForm.designation" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Phone</label>
                            <input type="text" x-model="contactForm.phone" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                            <input type="email" x-model="contactForm.email" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        </div>
                    </div>
                    <label class="flex items-center gap-2 text-sm text-slate-600">
                        <input type="checkbox" x-model="contactForm.is_primary" class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                        Primary contact
                    </label>

                    <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
                        <button type="button" @click="contactModalOpen = false" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Cancel</button>
                        <button type="submit" :disabled="savingContact" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700 disabled:opacity-60">Save</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Contract modal --}}
        <div x-show="contractModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/50" @click="contractModalOpen = false"></div>
            <div class="relative w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl">
                <h2 class="text-lg font-semibold text-ink" x-text="contractForm.id ? 'Edit Contract' : 'Add Contract'"></h2>
                <form @submit.prevent="submitContract()" class="mt-4 space-y-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Title</label>
                        <input type="text" x-model="contractForm.title" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        <p class="mt-1 text-xs text-rose-600" x-show="contractErrors.title" x-text="contractErrors.title?.[0]"></p>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Value ($)</label>
                            <input type="number" step="0.01" x-model="contractForm.value" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Status</label>
                            <select x-model="contractForm.status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                                @foreach (\App\Models\ClientContract::STATUSES as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Start Date</label>
                            <input type="date" x-model="contractForm.start_date" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">End Date</label>
                            <input type="date" x-model="contractForm.end_date" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                            <p class="mt-1 text-xs text-rose-600" x-show="contractErrors.end_date" x-text="contractErrors.end_date?.[0]"></p>
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Contract File</label>
                        <input type="file" @change="onContractFileChange($event)" class="block w-full text-sm text-slate-600">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Notes</label>
                        <textarea x-model="contractForm.notes" rows="2" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
                        <button type="button" @click="contractModalOpen = false" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Cancel</button>
                        <button type="submit" :disabled="savingContract" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700 disabled:opacity-60">Save</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Invoice modal --}}
        <div x-show="invoiceModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/50" @click="invoiceModalOpen = false"></div>
            <div class="relative w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl">
                <h2 class="text-lg font-semibold text-ink" x-text="invoiceForm.id ? 'Edit Invoice' : 'Add Invoice'"></h2>
                <form @submit.prevent="submitInvoice()" class="mt-4 space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Invoice Number</label>
                            <input type="text" x-model="invoiceForm.invoice_number" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                            <p class="mt-1 text-xs text-rose-600" x-show="invoiceErrors.invoice_number" x-text="invoiceErrors.invoice_number?.[0]"></p>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Amount ($)</label>
                            <input type="number" step="0.01" x-model="invoiceForm.amount" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                            <p class="mt-1 text-xs text-rose-600" x-show="invoiceErrors.amount" x-text="invoiceErrors.amount?.[0]"></p>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Status</label>
                            <select x-model="invoiceForm.status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                                @foreach (\App\Models\ClientInvoice::STATUSES as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Issue Date</label>
                            <input type="date" x-model="invoiceForm.issue_date" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                            <p class="mt-1 text-xs text-rose-600" x-show="invoiceErrors.issue_date" x-text="invoiceErrors.issue_date?.[0]"></p>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Due Date</label>
                            <input type="date" x-model="invoiceForm.due_date" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                            <p class="mt-1 text-xs text-rose-600" x-show="invoiceErrors.due_date" x-text="invoiceErrors.due_date?.[0]"></p>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Paid On</label>
                            <input type="date" x-model="invoiceForm.paid_at" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Invoice File</label>
                        <input type="file" @change="onInvoiceFileChange($event)" class="block w-full text-sm text-slate-600">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Notes</label>
                        <textarea x-model="invoiceForm.notes" rows="2" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
                        <button type="button" @click="invoiceModalOpen = false" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Cancel</button>
                        <button type="submit" :disabled="savingInvoice" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700 disabled:opacity-60">Save</button>
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
                    Are you sure you want to delete <span class="font-medium text-ink">{{ $client->displayName() }}</span>?
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
