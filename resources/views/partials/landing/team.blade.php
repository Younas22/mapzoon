@php
    $teamMembers = \App\Models\TeamMember::query()->visibleOnHomepage()->orderBy('display_order')->orderBy('name')->get();

    // Same gradient combinations the section originally shipped with, cycled
    // across however many members are configured so the look stays consistent
    // even though the roster is now admin-managed.
    $gradients = [
        'from-primary-400 to-primary-600',
        'from-slate-700 to-slate-900',
        'from-primary-500 to-slate-800',
        'from-slate-600 to-primary-700',
    ];
@endphp

<section id="team" class="relative bg-slate-50 py-20 lg:py-28">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="reveal mx-auto max-w-2xl text-center">
            <p class="text-sm font-semibold uppercase tracking-wider text-primary-600">Our Team</p>
            <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                Meet The Experts Behind MAPZOON
            </h2>
            <p class="mt-4 text-base text-slate-600 sm:text-lg">
                A passionate team helping local businesses dominate Google Maps and grow online.
            </p>
        </div>

        @if ($teamMembers->isNotEmpty())
            <ul class="mt-14 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5 lg:gap-6">
                @foreach ($teamMembers as $i => $member)
                    @php $isCeo = Str::contains(strtolower($member->designation ?? ''), ['ceo', 'founder']); @endphp
                    <li class="reveal reveal-delay-{{ ($i % 4) + 1 }} group relative overflow-hidden rounded-3xl border bg-white shadow-lg transition duration-300 hover:-translate-y-1.5 hover:shadow-2xl hover:ring-2 hover:ring-primary-300/60 {{ $isCeo ? 'col-span-2 sm:col-span-1 order-first sm:order-none z-10 scale-[1.07] border-primary-400 shadow-xl ring-2 ring-primary-300/60' : 'border-slate-200' }}">
                        <div class="relative overflow-hidden">
                            @if ($member->photoUrl())
                                <img src="{{ $member->photoUrl() }}" alt="{{ $member->name }}" class="aspect-square w-full object-cover transition duration-500 group-hover:scale-105">
                            @else
                                <div class="flex aspect-square items-center justify-center bg-gradient-to-br {{ $gradients[$i % count($gradients)] }} text-4xl font-extrabold text-white transition duration-500 group-hover:scale-105">
                                    {{ $member->initials() }}
                                </div>
                            @endif
                        </div>
                        <div class="p-5">
                            <h3 class="text-base font-bold text-slate-900">{{ $member->name }}</h3>
                            <p class="text-sm font-semibold text-primary-600">{{ $member->designation }}</p>
                            <!-- @if ($member->bio)
                                <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $member->bio }}</p>
                            @endif -->

                            <!-- @if ($member->linkedin_url || $member->email)
                                <div class="mt-4 flex items-center gap-3 border-t border-slate-100 pt-4">
                                    @if ($member->linkedin_url)
                                        <a href="{{ $member->linkedin_url }}" target="_blank" rel="noopener noreferrer" class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500 transition hover:bg-primary-50 hover:text-primary-600" title="LinkedIn" aria-label="{{ $member->name }} on LinkedIn">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                <path d="M4.98 3.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5zM3 9h4v12H3V9zm6.5 0h3.8v1.7h.05c.53-1 1.84-2.06 3.78-2.06 4.04 0 4.78 2.66 4.78 6.12V21h-4v-5.5c0-1.31-.02-3-1.83-3-1.83 0-2.1 1.43-2.1 2.9V21h-4V9z" />
                                            </svg>
                                        </a>
                                    @endif
                                    @if ($member->email)
                                        <a href="mailto:{{ $member->email }}" class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500 transition hover:bg-primary-50 hover:text-primary-600" title="Email {{ $member->name }}" aria-label="Email {{ $member->name }}">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <rect x="3" y="5" width="18" height="14" rx="2" />
                                                <path d="M3.5 6.5 12 13l8.5-6.5" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            @endif -->
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</section>
