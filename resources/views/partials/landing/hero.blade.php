<section id="hero" class="relative isolate overflow-hidden bg-black pt-[7.25rem] pb-16 lg:pt-40 lg:pb-24">

    {{-- Background --}}
    <div class="absolute inset-0 -z-20 bg-grid opacity-[0.05]" aria-hidden="true"></div>
    <div class="absolute -right-40 top-0 -z-10 h-[700px] w-[700px] rounded-full bg-primary-600/20 blur-[150px]" aria-hidden="true"></div>
    <div class="absolute right-[8%] bottom-0 -z-10 h-[400px] w-[400px] rounded-full bg-primary-500/15 blur-[120px]" aria-hidden="true"></div>
    <div class="absolute left-0 top-1/3 -z-10 h-[300px] w-[300px] rounded-full bg-primary-500/5 blur-[110px]" aria-hidden="true"></div>

    {{-- Full reference visual as the hero background (desktop only — covers the full section width). Matches the site's xl desktop-nav breakpoint so the image never collides with the text at in-between widths. --}}
    <div class="pointer-events-none absolute inset-0 -z-10 hidden xl:block" aria-hidden="true">
        <img src="{{ asset('images/hero-visual.png') }}" alt="" class="h-full w-full object-cover object-right">
        <div class="absolute inset-0" style="background: linear-gradient(to right, black 0%, rgba(0,0,0,0.4) 45%, transparent 75%);"></div>
    </div>

    <div class="relative mx-auto max-w-[1600px] px-6 lg:px-10">
        <div class="grid grid-cols-1 items-center gap-14 lg:gap-8">

            {{-- LEFT: Content --}}
            <div class="reveal text-center lg:text-left xl:max-w-xl">

                {{-- Badge --}}
                <p class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/8 px-4 py-1.5 text-sm font-medium text-slate-300 shadow-sm backdrop-blur-sm">
                    <span class="h-2 w-2 rounded-full bg-primary-400 animate-pulse-soft" aria-hidden="true"></span>
                    Trusted Local SEO &amp; Google Maps Growth Partner
                </p>

                {{-- Headline --}}
                <h1 class="mt-6 overflow-visible font-black leading-tight tracking-tighter text-white">
                    <span class="block whitespace-nowrap text-[6.6vw] sm:text-4xl md:text-[2.75rem] lg:text-4xl xl:text-5xl">More Visibility. More Calls.</span>
                    <span class="block whitespace-nowrap text-[6.6vw] text-primary-500 sm:text-4xl md:text-[2.75rem] lg:text-4xl xl:text-5xl">More Customers.</span>
                </h1>

                {{-- Subtext --}}
                <p class="mx-auto mt-6 max-w-[600px] text-base text-slate-400 sm:text-xl lg:mx-0">
                    Get your business seen on Google and turn local searches into real customers.
                </p>

                {{-- CTAs --}}
                <div class="mt-8 flex flex-wrap items-center justify-center gap-3 lg:justify-start">
                    <button type="button" id="open-quote-modal"
                        class="inline-flex flex-none items-center gap-1.5 rounded-none bg-primary-500 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-primary-500/40 transition hover:-translate-y-0.5 hover:bg-primary-400 hover:shadow-lg focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                        Get a Free SEO Audit
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14M13 6l6 6-6 6" />
                        </svg>
                    </button>
                    <a href="{{ route('services') }}"
                        class="inline-flex flex-none items-center gap-1.5 rounded-none border border-white/20 bg-white/8 px-6 py-3 text-sm font-semibold text-white shadow-sm backdrop-blur-sm transition hover:-translate-y-0.5 hover:bg-white/15 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                        View Our Services
                    </a>
                </div>

                {{-- Trust / Benefits row --}}
                <ul class="mt-8 flex flex-wrap items-center justify-center gap-x-6 gap-y-3 border-t border-white/10 pt-6 lg:justify-start">
                    @foreach ([
                        '500+ Happy Clients',
                        'Google Partner',
                        'Google Maps Platform',
                        'Results That Grow',
                    ] as $benefit)
                        <li class="flex items-center gap-1.5 text-sm font-medium text-slate-400">
                            <svg class="h-4 w-4 flex-none text-primary-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M5 12.5 9.5 17 19 7" />
                            </svg>
                            {{ $benefit }}
                        </li>
                    @endforeach
                </ul>
            </div>

        </div>

        {{-- Bottom trust bar --}}
        <div class="glass-dark reveal reveal-delay-3 mt-14 rounded-2xl px-6 py-5 lg:mt-20">
            <ul class="flex flex-wrap items-center justify-center gap-x-8 gap-y-4 lg:justify-between">
                @foreach ([
                    ['label' => 'Google Business Profile Optimization', 'path' => 'M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5z'],
                    ['label' => 'Local SEO Experts', 'path' => 'M11 4a7 7 0 1 0 4.32 12.5l5.09 5.09 1.41-1.41-5.09-5.09A7 7 0 0 0 11 4zm0 2a5 5 0 1 1 0 10 5 5 0 0 1 0-10z'],
                    ['label' => 'More Calls & Leads', 'path' => 'M5 4h3l2 5-2.5 1.5a11 11 0 0 0 5 5L14 13l5 2v3a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z'],
                    ['label' => 'Review & Reputation Management', 'path' => 'M12 2.5l2.9 6.1 6.6.7-4.9 4.6 1.3 6.6L12 17.3l-5.9 3.2 1.3-6.6-4.9-4.6 6.6-.7L12 2.5z'],
                    ['label' => 'Transparent Reporting', 'path' => 'M4 19h16M7 15v4M12 9v10M17 5v14'],
                ] as $item)
                    <li class="flex items-center gap-2.5 text-sm font-medium text-slate-300">
                        <svg class="h-4 w-4 flex-none text-primary-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="{{ $item['path'] }}" />
                        </svg>
                        {{ $item['label'] }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>
