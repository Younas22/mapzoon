@php
    // The password is intentionally omitted here — it is only ever fetched
    // on demand via the reveal endpoint, which records who viewed it and when.
    $credentialEditData = [
        'id' => $credential->id,
        'platform' => $credential->platform,
        'label' => $credential->label,
        'username' => $credential->username,
        'recovery_email' => $credential->recovery_email,
        'recovery_phone' => $credential->recovery_phone,
        'url' => $credential->url,
        'notes' => $credential->notes,
    ];

    $hasPassword = filled($credential->password);
@endphp

<div id="credential-{{ $credential->id }}" data-credential-item class="rounded-xl border border-slate-200 p-4">
    <div class="flex items-start justify-between gap-3">
        <div>
            <div class="flex items-center gap-2">
                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">{{ $credential->platformLabel() }}</span>
                <p class="font-medium text-ink">{{ $credential->label }}</p>
            </div>
            @if ($credential->url)
                <a href="{{ $credential->url }}" target="_blank" rel="noopener noreferrer" class="text-xs text-primary-600 hover:text-primary-700">{{ $credential->url }}</a>
            @endif

            <div class="mt-2 grid grid-cols-1 gap-1 text-sm text-slate-600 sm:grid-cols-2">
                <p><span class="text-xs text-slate-400">Username:</span> {{ $credential->username ?: '—' }}</p>
                <p class="flex items-center gap-2">
                    <span class="text-xs text-slate-400">Password:</span>
                    @if ($hasPassword)
                        <span x-show="! isRevealed({{ $credential->id }})">••••••••</span>
                        <span x-show="isRevealed({{ $credential->id }})" x-cloak x-text="revealedPassword({{ $credential->id }})"></span>
                        @can('reveal', $credential)
                            <button type="button" @click="toggleReveal({{ $credential->id }})" :disabled="revealingCredentialId === {{ $credential->id }}" class="text-xs font-medium text-primary-600 hover:text-primary-700 disabled:opacity-50">
                                <span x-show="! isRevealed({{ $credential->id }})">Show</span>
                                <span x-show="isRevealed({{ $credential->id }})" x-cloak>Hide</span>
                            </button>
                        @endcan
                    @else
                        <span>—</span>
                    @endif
                </p>
                @if ($credential->recovery_email)
                    <p><span class="text-xs text-slate-400">Recovery Email:</span> {{ $credential->recovery_email }}</p>
                @endif
                @if ($credential->recovery_phone)
                    <p><span class="text-xs text-slate-400">Recovery Phone:</span> {{ $credential->recovery_phone }}</p>
                @endif
            </div>

            @if ($credential->notes)
                <p class="mt-2 whitespace-pre-line text-sm text-slate-600">{{ $credential->notes }}</p>
            @endif
        </div>

        <div class="flex flex-none items-center gap-3">
            <button type="button" class="text-sm font-medium text-slate-500 hover:text-ink" @click="openHistory({{ $credential->id }})">History</button>
            @can('update', $credential)
                <button type="button" class="text-sm font-medium text-primary-600 hover:text-primary-700" @click="openEditCredential(@js($credentialEditData))">Edit</button>
            @endcan
            @can('delete', $credential)
                <button type="button" class="text-sm font-medium text-rose-600 hover:text-rose-700" @click="removeCredential({{ $credential->id }}, $event)">Remove</button>
            @endcan
        </div>
    </div>
</div>
