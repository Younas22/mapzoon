<li data-milestone-item class="flex items-center gap-3 rounded-lg border border-slate-100 px-3 py-2">
    <input
        type="checkbox"
        @checked($milestone->is_completed)
        @change="toggleMilestone({{ $milestone->id }}, $event)"
        class="rounded border-slate-300 text-primary-600 focus:ring-primary-500"
    >
    <span data-milestone-title class="flex-1 text-sm {{ $milestone->is_completed ? 'text-slate-400 line-through' : 'text-ink' }}">
        {{ $milestone->title }}
        @if ($milestone->date)
            <span class="text-xs text-slate-400">· {{ $milestone->date->format('M d, Y') }}</span>
        @endif
    </span>
    <button type="button" @click="removeMilestone({{ $milestone->id }}, $event)" class="text-xs font-medium text-rose-600 hover:text-rose-700">Remove</button>
</li>
