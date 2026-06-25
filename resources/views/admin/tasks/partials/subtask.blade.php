<li data-subtask-item class="flex items-center gap-3 rounded-lg border border-slate-100 px-3 py-2">
    <input
        type="checkbox"
        @checked($subtask->is_completed)
        @change="toggleSubtask({{ $subtask->id }}, $event)"
        class="rounded border-slate-300 text-primary-600 focus:ring-primary-500"
    >
    <span data-subtask-title class="flex-1 text-sm {{ $subtask->is_completed ? 'text-slate-400 line-through' : 'text-ink' }}">{{ $subtask->title }}</span>
    <button type="button" @click="removeSubtask({{ $subtask->id }}, $event)" class="text-xs font-medium text-rose-600 hover:text-rose-700">Remove</button>
</li>
