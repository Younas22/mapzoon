<section id="hero" class="relative overflow-hidden bg-black pt-28 pb-20 lg:pt-36 lg:pb-28">

    {{-- Background grid pattern --}}
    <div class="absolute inset-0 -z-20 bg-grid opacity-[0.06]" aria-hidden="true"></div>

    {{-- Glowing orbs --}}
    <div class="absolute -left-20 top-0 -z-10 h-[600px] w-[600px] rounded-full bg-primary-600/25 blur-[130px]" aria-hidden="true"></div>
    <div class="absolute -right-20 top-20 -z-10 h-[500px] w-[500px] rounded-full bg-violet-600/15 blur-[120px]" aria-hidden="true"></div>
    <div class="absolute left-1/2 -z-10 top-1/2 h-[400px] w-[800px] -translate-x-1/2 rounded-full bg-primary-500/10 blur-[100px]" aria-hidden="true"></div>
    <div class="absolute left-1/4 bottom-0 -z-10 h-[300px] w-[400px] rounded-full bg-cyan-500/10 blur-[100px]" aria-hidden="true"></div>

    {{-- Decorative floating rings --}}
    <div class="absolute right-[8%] top-[15%] -z-10 h-64 w-64 rounded-full border border-white/5" aria-hidden="true"></div>
    <div class="absolute right-[10%] top-[13%] -z-10 h-40 w-40 rounded-full border border-primary-500/20" aria-hidden="true"></div>
    <div class="absolute left-[6%] bottom-[20%] -z-10 h-48 w-48 rounded-full border border-white/5" aria-hidden="true"></div>

    <div class="relative mx-auto max-w-4xl px-6 text-center lg:max-w-6xl lg:px-8">
        <div class="reveal">

            {{-- Badge --}}
            <p class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/8 px-4 py-1.5 text-sm font-medium text-slate-300 shadow-sm backdrop-blur-sm">
                <span class="h-2 w-2 rounded-full bg-primary-400 animate-pulse-soft" aria-hidden="true"></span>
                Local SEO &amp; Growth Partner for 350+ Businesses
            </p>

            {{-- Headline --}}
            <h1 class="mt-6 overflow-visible text-4xl font-black leading-tight tracking-tighter text-white sm:text-5xl sm:leading-[1.05] lg:text-6xl lg:leading-[1.08]">
                Not Getting <span class="text-primary-400 underline decoration-white decoration-[5px] underline-offset-[1px]">Local Customers?</span><br>
                <span class="text-gradient">Rank Higher</span> on Google Maps
            </h1>

            {{-- Subtext --}}
            <p class="mx-auto mt-6 max-w-2xl text-base text-slate-400 sm:text-xl">
                Increase your Google rankings, attract more leads, and grow faster with Local SEO, websites, and POS solutions.
            </p>

            {{-- CTAs --}}
            <div class="mt-8 flex items-center justify-center gap-2">
                <select id="hero-service-pick"
                    class="rounded-xl border border-white/15 bg-white/10 px-3 py-2.5 text-sm font-medium text-white shadow-sm backdrop-blur-sm focus:border-primary-400 focus:outline-none focus:ring-2 focus:ring-primary-500/30 max-w-[190px] sm:max-w-[230px]">
                    <option value="Google Business Profile Optimization" class="text-slate-900">Google Business Profile</option>
                    <option value="Local SEO" class="text-slate-900">Local SEO</option>
                    <option value="Website Development" class="text-slate-900">Website Development</option>
                    <option value="POS &amp; Billing System" class="text-slate-900">POS &amp; Billing System</option>
                </select>
                <button type="button" id="open-quote-modal"
                    class="inline-flex flex-none items-center gap-1.5 rounded-xl bg-primary-500 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-primary-500/40 transition hover:-translate-y-0.5 hover:bg-primary-400 hover:shadow-lg focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                    Get Free Quote
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 12h14M13 6l6 6-6 6" />
                    </svg>
                </button>
            </div>

            {{-- Trusted Platforms --}}
            <div class="mt-10 border-t border-white/10 pt-8">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Trusted Tools &amp; Platforms</p>

                <div class="mt-5 flex flex-wrap items-center justify-center gap-3">

                    {{-- Google --}}
                    <span class="flex items-center gap-2 rounded-xl border border-white/10 bg-white/8 px-4 py-2.5 shadow-sm backdrop-blur-sm">
                        <svg class="h-6 w-6 flex-none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                        <span class="text-sm font-bold text-slate-300">Google</span>
                    </span>

                    {{-- Google Maps --}}
                    <span class="flex items-center gap-2 rounded-xl border border-white/10 bg-white/8 px-4 py-2.5 shadow-sm backdrop-blur-sm">
                        <svg class="h-6 w-6 flex-none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" fill="#EA4335"/>
                            <circle cx="12" cy="9" r="2.5" fill="white"/>
                        </svg>
                        <span class="text-sm font-bold text-slate-300">Google Maps</span>
                    </span>

                    {{-- Meta --}}
                    <span class="flex items-center gap-2 rounded-xl border border-white/10 bg-white/8 px-4 py-2.5 shadow-sm backdrop-blur-sm">
                        <svg class="h-6 w-6 flex-none" viewBox="0 0 24 24" fill="#0866FF" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M12 2.04C6.48 2.04 2 6.52 2 12.04c0 5.02 3.66 9.18 8.44 9.96v-7.04H7.9v-2.92h2.54V9.9c0-2.51 1.49-3.89 3.77-3.89 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.81h2.77l-.44 2.92h-2.33v7.04C18.34 21.22 22 17.06 22 12.04 22 6.52 17.52 2.04 12 2.04z"/>
                        </svg>
                        <span class="text-sm font-bold text-slate-300">Meta</span>
                    </span>

                    {{-- Yelp --}}
                    <span class="flex items-center gap-2 rounded-xl border border-white/10 bg-white/8 px-4 py-2.5 shadow-sm backdrop-blur-sm">
                        <svg class="h-6 w-6 flex-none" viewBox="0 0 60 60" fill="#D32323" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M36.23 26.37c.74-.89 2.08-.81 2.78.11l8.05 10.85c.7.93.3 2.27-.83 2.64l-8.86 2.85c-1.13.36-2.25-.54-2.15-1.72l.81-12.78c.04-.73.43-1.43 1.2-1.95zM26.5 46.15l-3.53 9.22c-.44 1.15.43 2.35 1.64 2.23l9.32-.92c1.21-.12 1.84-1.47 1.14-2.44l-5.79-8.3c-.74-1.06-2.33-.93-2.78.21zM14.7 33.93l-9.5 1.7c-1.17.21-1.65 1.6-.87 2.51l6.18 7.09c.79.9 2.23.68 2.71-.39l3.32-8.78c.43-1.14-.6-2.32-1.84-2.13zM22.8 13.56l-6.18-7.09c-.79-.9-2.23-.68-2.71.39l-3.32 8.78c-.43 1.14.6 2.32 1.84 2.13l9.5-1.7c1.17-.21 1.65-1.6.87-2.51zM29.5 3.2l-1.05 12.77c-.08.97.88 1.72 1.82 1.41l8.87-2.85c.94-.3 1.22-1.49.52-2.2L31.9 3.54C31.2 2.83 29.61 2.78 29.5 3.2z"/>
                        </svg>
                        <span class="text-sm font-bold text-slate-300">Yelp</span>
                    </span>

                    {{-- Semrush --}}
                    <span class="flex items-center gap-2 rounded-xl border border-white/10 bg-white/8 px-4 py-2.5 shadow-sm backdrop-blur-sm">
                        <svg class="h-6 w-6 flex-none" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <rect width="40" height="40" rx="8" fill="#FF6A1A"/>
                            <text x="50%" y="54%" dominant-baseline="middle" text-anchor="middle" fill="white" font-size="22" font-weight="900" font-family="Arial, sans-serif">S</text>
                        </svg>
                        <span class="text-sm font-bold text-slate-300">Semrush</span>
                    </span>

                    {{-- Ahrefs --}}
                    <span class="flex items-center gap-2 rounded-xl border border-white/10 bg-white/8 px-4 py-2.5 shadow-sm backdrop-blur-sm">
                        <svg class="h-6 w-6 flex-none" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <rect width="40" height="40" rx="8" fill="#1D52F0"/>
                            <text x="50%" y="54%" dominant-baseline="middle" text-anchor="middle" fill="white" font-size="20" font-weight="900" font-family="Arial, sans-serif">A</text>
                        </svg>
                        <span class="text-sm font-bold text-slate-300">Ahrefs</span>
                    </span>

                </div>

                <p class="mt-4 text-xs text-slate-600">Certified Google Maps &amp; Local SEO specialists · 350+ local businesses scaled worldwide</p>
            </div>

        </div>
    </div>
</section>
