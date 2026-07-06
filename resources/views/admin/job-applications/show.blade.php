@php
    $statusColors = [
        'new' => 'bg-slate-100 text-slate-600',
        'reviewed' => 'bg-amber-50 text-amber-700',
        'shortlisted' => 'bg-primary-50 text-primary-700',
        'rejected' => 'bg-rose-50 text-rose-700',
    ];
@endphp

<x-admin-layout :title="$application->fullName()">
    <div
        x-data="jobApplicationShow(@js(['baseUrl' => url('/admin/job-applications/'.$application->id), 'indexUrl' => route('admin.job-applications.index')]))"
        x-cloak
        class="mx-auto max-w-4xl space-y-6"
    >
        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div class="flex items-start gap-4">
                    @if ($application->photoUrl())
                        <img src="{{ $application->photoUrl() }}" alt="{{ $application->fullName() }}" class="h-16 w-16 rounded-full object-cover">
                    @endif
                    <div>
                        <h1 class="text-lg font-semibold text-ink">{{ $application->fullName() }}</h1>
                        <p class="text-sm text-slate-500">{{ $application->position }}</p>
                        <div class="mt-2 flex flex-wrap items-center gap-2">
                            <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $statusColors[$application->status] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $application->statusLabel() }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    @can('delete', $application)
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
                <p class="mt-1 text-sm font-medium text-ink">{{ $application->email }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Phone</p>
                <p class="mt-1 text-sm font-medium text-ink">{{ $application->phone }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">WhatsApp</p>
                <p class="mt-1 text-sm font-medium text-ink">{{ $application->whatsapp ?: '—' }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">City</p>
                <p class="mt-1 text-sm font-medium text-ink">{{ $application->city ?: '—' }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Experience Level</p>
                <p class="mt-1 text-sm font-medium text-ink">{{ $application->experience_level ?: '—' }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Education Level</p>
                <p class="mt-1 text-sm font-medium text-ink">{{ $application->education_level ?: '—' }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Availability</p>
                <p class="mt-1 text-sm font-medium text-ink">{{ $application->availability ?: '—' }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Salary Expectation</p>
                <p class="mt-1 text-sm font-medium text-ink">{{ $application->salary_expectation ?: '—' }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">LinkedIn</p>
                <p class="mt-1 text-sm font-medium text-ink">
                    @if ($application->linkedin_url)
                        <a href="{{ $application->linkedin_url }}" target="_blank" rel="noopener" class="text-primary-600 hover:underline">View Profile</a>
                    @else
                        —
                    @endif
                </p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Portfolio</p>
                <p class="mt-1 text-sm font-medium text-ink">
                    @if ($application->portfolio_url)
                        <a href="{{ $application->portfolio_url }}" target="_blank" rel="noopener" class="text-primary-600 hover:underline">View Portfolio</a>
                    @else
                        —
                    @endif
                </p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">CV / Resume</p>
                <p class="mt-1 text-sm font-medium text-ink">
                    @if ($application->cvUrl())
                        <a href="{{ $application->cvUrl() }}" target="_blank" rel="noopener" class="text-primary-600 hover:underline">Download CV</a>
                    @else
                        —
                    @endif
                </p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Applied On</p>
                <p class="mt-1 text-sm font-medium text-ink">{{ $application->created_at->format('M d, Y') }}</p>
            </div>
        </div>

        @can('update', $application)
            <div class="rounded-2xl border border-slate-200 bg-white p-6">
                <h2 class="mb-4 text-base font-semibold text-ink">Status</h2>

                <div class="max-w-xs">
                    <select @change="changeStatus($event.target.value)"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        @foreach (\App\Models\JobApplication::STATUSES as $value => $label)
                            <option value="{{ $value }}" @selected($application->status === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endcan

        @if ($application->cover_letter)
            <div class="rounded-2xl border border-slate-200 bg-white p-6">
                <h2 class="mb-2 text-base font-semibold text-ink">Cover Letter</h2>
                <p class="whitespace-pre-line text-sm text-slate-600">{{ $application->cover_letter }}</p>
            </div>
        @endif

        {{-- Delete confirmation modal --}}
        <div x-show="deleteModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/50" @click="deleteModalOpen = false"></div>

            <div class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl">
                <h2 class="text-lg font-semibold text-ink">Delete application?</h2>
                <p class="mt-2 text-sm text-slate-500">
                    Are you sure you want to delete <span class="font-medium text-ink">{{ $application->fullName() }}</span>? This cannot be undone.
                </p>

                <div class="mt-5 flex justify-end gap-3">
                    <button type="button" @click="deleteModalOpen = false" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">
                        Cancel
                    </button>
                    <button
                        type="button"
                        @click="deleteApplication()"
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
