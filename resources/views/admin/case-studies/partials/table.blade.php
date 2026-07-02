@php
    $sort = request()->query('sort', 'display_order');
    $dir = request()->query('dir', 'asc');
    $arrow = fn (string $field) => $sort === $field ? ($dir === 'asc' ? '↑' : '↓') : '';
@endphp

<div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white">
    <table class="w-full min-w-[760px] text-left text-sm">
        <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
            <tr>
                <th class="cursor-pointer select-none px-4 py-3" data-sort="title">Case Study {{ $arrow('title') }}</th>
                <th class="px-4 py-3">Owner</th>
                <th class="px-4 py-3">Status</th>
                <th class="cursor-pointer select-none px-4 py-3" data-sort="display_order">Order {{ $arrow('display_order') }}</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($caseStudies as $caseStudy)
                <tr>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            @if ($caseStudy->imageUrl())
                                <img src="{{ $caseStudy->imageUrl() }}" alt="" class="h-10 w-14 flex-none rounded-lg object-cover">
                            @else
                                <span class="flex h-10 w-14 flex-none items-center justify-center rounded-lg bg-slate-100 text-xs text-slate-400">No image</span>
                            @endif
                            <div>
                                <span class="block font-medium text-ink">{{ $caseStudy->title }}</span>
                                @if ($caseStudy->gmb_link)
                                    <span class="mt-0.5 inline-flex rounded-full bg-primary-50 px-2 py-0.5 text-xs font-medium text-primary-700">GMB Listed</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-slate-500">{{ $caseStudy->owner_name ?? '—' }}</td>
                    <td class="px-4 py-3">
                        <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $caseStudy->is_active ? 'bg-primary-50 text-primary-700' : 'bg-slate-100 text-slate-600' }}">
                            {{ $caseStudy->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-slate-500">{{ $caseStudy->display_order }}</td>
                    <td class="px-4 py-3 text-right">
                        <div class="inline-flex items-center gap-3">
                            @if ($caseStudy->is_active)
                                <a href="{{ route('case-studies.show', $caseStudy->slug) }}" target="_blank" class="text-sm font-medium text-slate-600 hover:text-ink">View</a>
                            @endif
                            @can('update', $caseStudy)
                                <a href="{{ route('admin.case-studies.edit', $caseStudy) }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">Edit</a>
                            @endcan
                            @can('delete', $caseStudy)
                                <button type="button" class="text-sm font-medium text-rose-600 hover:text-rose-700"
                                        @click="confirmDelete({{ $caseStudy->id }}, '{{ addslashes($caseStudy->title) }}')">
                                    Delete
                                </button>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-10 text-center text-slate-400">No case studies found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($caseStudies->hasPages())
        <div class="flex items-center justify-between border-t border-slate-200 px-4 py-3 text-sm text-slate-500">
            <span>Page {{ $caseStudies->currentPage() }} of {{ $caseStudies->lastPage() }} ({{ $caseStudies->total() }} case studies)</span>
            <div class="flex gap-2">
                <button type="button" data-page="{{ $caseStudies->currentPage() - 1 }}" @if ($caseStudies->onFirstPage()) disabled @endif
                        class="rounded-lg border border-slate-200 px-3 py-1.5 disabled:cursor-not-allowed disabled:opacity-40 hover:bg-slate-50">
                    Previous
                </button>
                <button type="button" data-page="{{ $caseStudies->currentPage() + 1 }}" @if (! $caseStudies->hasMorePages()) disabled @endif
                        class="rounded-lg border border-slate-200 px-3 py-1.5 disabled:cursor-not-allowed disabled:opacity-40 hover:bg-slate-50">
                    Next
                </button>
            </div>
        </div>
    @endif
</div>
