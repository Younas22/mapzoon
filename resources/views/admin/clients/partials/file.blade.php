<div data-file-item class="flex items-center justify-between gap-3 rounded-lg border border-slate-100 px-3 py-2">
    <a href="{{ route('admin.clients.files.download', [$client, $clientFile]) }}" class="flex items-center gap-2 text-sm text-ink hover:text-primary-600">
        <svg class="h-4 w-4 flex-none text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
        <span>
            <span class="block font-medium">{{ $clientFile->original_name }}</span>
            <span class="block text-xs text-slate-400">{{ $clientFile->sizeForHumans() }} · {{ $clientFile->user?->name ?? 'Unknown' }}</span>
        </span>
    </a>
    @can('update', $client)
        <button type="button" @click="removeFile({{ $clientFile->id }}, $event)" class="text-xs font-medium text-rose-600 hover:text-rose-700">Remove</button>
    @endcan
</div>
