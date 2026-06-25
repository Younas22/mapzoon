<div class="rounded-xl border border-slate-100 bg-white p-4">
    <div class="mb-1 flex items-center justify-between">
        <span class="text-sm font-medium text-ink">{{ $comment->user?->name ?? 'Unknown' }}</span>
        <span class="text-xs text-slate-400">{{ $comment->created_at->format('M d, Y \a\t g:i A') }}</span>
    </div>
    <p class="whitespace-pre-line text-sm text-slate-600">{{ $comment->comment }}</p>
</div>
