<section id="services" class="relative bg-white py-20 lg:py-28">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="reveal mx-auto max-w-2xl text-center">
            <p class="text-sm font-semibold uppercase tracking-wider text-primary-600">Our Services</p>
            <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                One Core Focus. Everything Else Built Around It.
            </h2>
            <p class="mt-4 text-base text-slate-600 sm:text-lg">
                Local SEO is what we do best — backed by the website, profile, and billing tools to close the loop.
            </p>
        </div>

        {{-- Featured core service: Local SEO --}}
        <div class="reveal reveal-delay-1 group relative mt-14 overflow-hidden rounded-[2.5rem] bg-black p-10 text-white shadow-2xl sm:p-14">
            <div class="absolute -right-24 -top-24 h-96 w-96 rounded-full bg-primary-500/30 blur-3xl" aria-hidden="true"></div>
            <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-primary-500/20 blur-3xl" aria-hidden="true"></div>

            <div class="relative z-10 flex flex-col items-start gap-10 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-xl">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-primary-500/15 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-primary-400">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 3l1.8 5.4L19 10l-5.2 1.6L12 17l-1.8-5.4L5 10l5.2-1.6L12 3z" />
                        </svg>
                        Our Core Service
                    </span>
                    <h3 class="mt-5 text-3xl font-extrabold sm:text-4xl">Local SEO</h3>
                    <p class="mt-4 text-base leading-relaxed text-slate-300 sm:text-lg">
                        On-page, technical, and content SEO tailored to rank your business across local search results — so customers find you before they find your competitors.
                    </p>
                    <a href="{{ route('contact.page') }}" class="mt-7 inline-flex items-center gap-2 rounded-none bg-primary-500 px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-primary-500/30 transition hover:-translate-y-0.5 hover:bg-primary-600">
                        Get a Free Quote
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14M13 6l6 6-6 6" />
                        </svg>
                    </a>
                </div>

                <div class="flex h-28 w-28 flex-none items-center justify-center rounded-3xl bg-primary-500/10 text-primary-400 lg:h-36 lg:w-36">
                    <svg class="h-14 w-14 lg:h-16 lg:w-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="9" />
                        <path d="M3 12h18" />
                        <path d="M12 3c2.5 2.5 4 5.6 4 9s-1.5 6.5-4 9c-2.5-2.5-4-5.6-4-9s1.5-6.5 4-9z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Supporting services --}}
        <ul class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-3">
            <li class="reveal reveal-delay-2 group relative overflow-hidden rounded-3xl border border-slate-200 bg-white p-7 shadow-lg transition duration-300 hover:-translate-y-1.5 hover:border-primary-200 hover:shadow-2xl sm:p-8">
                <div class="absolute -left-8 -top-8 h-32 w-32 rounded-full bg-primary-200/40 blur-2xl transition duration-500 group-hover:bg-primary-300/50" aria-hidden="true"></div>

                <div class="relative z-10">
                    <div class="glass flex h-14 w-14 items-center justify-center rounded-2xl text-primary-600 shadow-md">
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="4" width="18" height="16" rx="2" />
                            <path d="M3 9h18" />
                            <circle cx="6.5" cy="6.5" r="0.6" fill="currentColor" stroke="none" />
                            <circle cx="9" cy="6.5" r="0.6" fill="currentColor" stroke="none" />
                            <path d="M9.5 13.5l-2 2 2 2M14.5 13.5l2 2-2 2" />
                        </svg>
                    </div>
                    <h3 class="mt-5 text-lg font-bold text-slate-900">Website</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">
                        Fast, conversion-focused websites that turn visitors into booked customers.
                    </p>
                    <a href="{{ route('contact.page') }}" class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-primary-600 transition group-hover:gap-2.5">
                        Learn more
                        <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14M13 6l6 6-6 6" />
                        </svg>
                    </a>
                </div>
            </li>

            <li class="reveal reveal-delay-3 group relative overflow-hidden rounded-3xl border border-slate-200 bg-white p-7 shadow-lg transition duration-300 hover:-translate-y-1.5 hover:border-primary-200 hover:shadow-2xl sm:p-8">
                <div class="absolute -left-8 -top-8 h-32 w-32 rounded-full bg-primary-200/40 blur-2xl transition duration-500 group-hover:bg-primary-300/50" aria-hidden="true"></div>

                <div class="relative z-10">
                    <div class="glass flex h-14 w-14 items-center justify-center rounded-2xl text-primary-600 shadow-md">
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 21s-7-7.58-7-12a7 7 0 1 1 14 0c0 4.42-7 12-7 12z" />
                            <path d="M9 9.5l2 2 4-4.5" />
                        </svg>
                    </div>
                    <h3 class="mt-5 text-lg font-bold text-slate-900">Google Business Profile Optimization</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">
                        Complete setup, verification, and ongoing optimization to maximize visibility.
                    </p>
                    <a href="{{ route('contact.page') }}" class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-primary-600 transition group-hover:gap-2.5">
                        Learn more
                        <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14M13 6l6 6-6 6" />
                        </svg>
                    </a>
                </div>
            </li>

            <li class="reveal reveal-delay-4 group relative overflow-hidden rounded-3xl border border-slate-200 bg-white p-7 shadow-lg transition duration-300 hover:-translate-y-1.5 hover:border-primary-200 hover:shadow-2xl sm:p-8">
                <div class="absolute -left-8 -top-8 h-32 w-32 rounded-full bg-primary-200/40 blur-2xl transition duration-500 group-hover:bg-primary-300/50" aria-hidden="true"></div>

                <div class="relative z-10">
                    <div class="glass flex h-14 w-14 items-center justify-center rounded-2xl text-primary-600 shadow-md">
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="6" width="18" height="13" rx="2" />
                            <circle cx="12" cy="12.5" r="2.5" />
                            <path d="M7 6V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v1" />
                        </svg>
                    </div>
                    <h3 class="mt-5 text-lg font-bold text-slate-900">POS Solutions</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">
                        Cloud-based sales, invoicing, and inventory built for local businesses.
                    </p>
                    <a href="{{ route('contact.page') }}" class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-primary-600 transition group-hover:gap-2.5">
                        Learn more
                        <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14M13 6l6 6-6 6" />
                        </svg>
                    </a>
                </div>
            </li>
        </ul>
    </div>
</section>
