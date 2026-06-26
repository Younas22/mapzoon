@php
    $contractStatusColors = [
        'active' => 'bg-primary-50 text-primary-700',
        'expired' => 'bg-slate-100 text-slate-600',
        'terminated' => 'bg-rose-50 text-rose-700',
    ];

    $contractEditData = [
        'id' => $contract->id,
        'title' => $contract->title,
        'value' => $contract->value,
        'start_date' => $contract->start_date?->format('Y-m-d'),
        'end_date' => $contract->end_date?->format('Y-m-d'),
        'status' => $contract->status,
        'notes' => $contract->notes,
    ];
@endphp

<div id="contract-{{ $contract->id }}" data-contract-item class="rounded-xl border border-slate-200 p-4">
    <div class="flex items-start justify-between gap-3">
        <div>
            <p class="font-medium text-ink">{{ $contract->title }}</p>
            <p class="mt-1 text-xs text-slate-500">
                {{ $contract->start_date?->format('M d, Y') ?? '—' }} &rarr; {{ $contract->end_date?->format('M d, Y') ?? '—' }}
                @if ($contract->value)
                    · ${{ number_format($contract->value, 2) }}
                @endif
            </p>
            @if ($contract->file_path)
                <a href="{{ route('admin.clients.contracts.download', [$client, $contract]) }}" class="mt-1 inline-flex items-center gap-1 text-xs font-medium text-primary-600 hover:text-primary-700">
                    {{ $contract->file_original_name }}
                </a>
            @endif
        </div>

        <div class="flex flex-none items-center gap-3">
            <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $contractStatusColors[$contract->status] ?? 'bg-slate-100 text-slate-600' }}">
                {{ $contract->statusLabel() }}
            </span>

            @can('update', $client)
                <button type="button" class="text-sm font-medium text-primary-600 hover:text-primary-700" @click="openEditContract(@js($contractEditData))">Edit</button>
                <button type="button" class="text-sm font-medium text-rose-600 hover:text-rose-700" @click="removeContract({{ $contract->id }}, $event)">Remove</button>
            @endcan
        </div>
    </div>

    @if ($contract->notes)
        <p class="mt-2 whitespace-pre-line text-sm text-slate-600">{{ $contract->notes }}</p>
    @endif
</div>
