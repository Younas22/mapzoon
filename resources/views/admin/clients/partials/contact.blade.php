<div id="contact-{{ $contact->id }}" data-contact-item class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 p-4">
    <div>
        <p class="font-medium text-ink">
            {{ $contact->name }}
            @if ($contact->is_primary)
                <span class="ml-1 rounded-full bg-primary-50 px-2 py-0.5 text-xs font-medium text-primary-700">Primary</span>
            @endif
        </p>
        @if ($contact->designation)
            <p class="text-xs text-slate-500">{{ $contact->designation }}</p>
        @endif
        <p class="mt-1 text-xs text-slate-500">{{ $contact->email ?: '—' }} · {{ $contact->phone ?: '—' }}</p>
    </div>

    @can('update', $client)
        <div class="flex flex-none gap-3">
            <button type="button" class="text-sm font-medium text-primary-600 hover:text-primary-700"
                    @click="openEditContact(@js($contact->only('id', 'name', 'designation', 'phone', 'email', 'is_primary')))">
                Edit
            </button>
            <button type="button" class="text-sm font-medium text-rose-600 hover:text-rose-700" @click="removeContact({{ $contact->id }}, $event)">
                Remove
            </button>
        </div>
    @endcan
</div>
