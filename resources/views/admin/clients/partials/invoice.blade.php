@php
    $invoiceStatusColors = [
        'unpaid' => 'bg-slate-100 text-slate-600',
        'paid' => 'bg-primary-50 text-primary-700',
        'overdue' => 'bg-rose-50 text-rose-700',
        'cancelled' => 'bg-slate-100 text-slate-400',
    ];

    $invoiceEditData = [
        'id' => $invoice->id,
        'invoice_number' => $invoice->invoice_number,
        'amount' => $invoice->amount,
        'status' => $invoice->status,
        'issue_date' => $invoice->issue_date?->format('Y-m-d'),
        'due_date' => $invoice->due_date?->format('Y-m-d'),
        'paid_at' => $invoice->paid_at?->format('Y-m-d'),
        'notes' => $invoice->notes,
    ];
@endphp

<div id="invoice-{{ $invoice->id }}" data-invoice-item class="rounded-xl border border-slate-200 p-4">
    <div class="flex items-start justify-between gap-3">
        <div>
            <p class="font-medium text-ink">{{ $invoice->invoice_number }} · ${{ number_format($invoice->amount, 2) }}</p>
            <p class="mt-1 text-xs {{ $invoice->isOverdue() ? 'font-medium text-rose-600' : 'text-slate-500' }}">
                Issued {{ $invoice->issue_date?->format('M d, Y') }}
                @if ($invoice->due_date)
                    · Due {{ $invoice->due_date->format('M d, Y') }}
                @endif
            </p>
            @if ($invoice->file_path)
                <a href="{{ route('admin.clients.invoices.download', [$client, $invoice]) }}" class="mt-1 inline-flex items-center gap-1 text-xs font-medium text-primary-600 hover:text-primary-700">
                    {{ $invoice->file_original_name }}
                </a>
            @endif
        </div>

        <div class="flex flex-none items-center gap-3">
            <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $invoiceStatusColors[$invoice->status] ?? 'bg-slate-100 text-slate-600' }}">
                {{ $invoice->statusLabel() }}
            </span>

            @can('update', $client)
                <button type="button" class="text-sm font-medium text-primary-600 hover:text-primary-700" @click="openEditInvoice(@js($invoiceEditData))">Edit</button>
                <button type="button" class="text-sm font-medium text-rose-600 hover:text-rose-700" @click="removeInvoice({{ $invoice->id }}, $event)">Remove</button>
            @endcan
        </div>
    </div>

    @if ($invoice->notes)
        <p class="mt-2 whitespace-pre-line text-sm text-slate-600">{{ $invoice->notes }}</p>
    @endif
</div>
