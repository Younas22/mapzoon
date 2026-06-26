<?php $settings = \App\Models\SiteSetting::current(); ?>
<footer class="relative border-t border-white/10 bg-slate-950 pt-16 pb-8">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-12 lg:gap-8">

            <div class="sm:col-span-2 lg:col-span-4">
                <a href="/" class="inline-flex items-center gap-2.5">
                    <img src="{{ $settings->logoLightUrl() }}" alt="{{ $settings->company_name ?? 'MAPZOON' }}" class="h-9 w-auto" />
                    <span class="text-xl font-extrabold tracking-tight text-white">{{ $settings->company_name ?? 'MAPZOON' }}</span>
                </a>
                <p class="mt-3 text-sm font-semibold text-primary-400">{{ $settings->tagline ?? 'Rank Higher. Get More Leads.' }}</p>
                <p class="mt-3 max-w-sm text-sm leading-relaxed text-slate-400">
                    {{ $settings->footer_description ?? 'Helping local businesses dominate Google Maps, build modern websites, and run smarter operations — all from one growth partner.' }}
                </p>
                @if ($settings->working_hours)
                    <div class="mt-4 max-w-sm text-sm leading-relaxed text-slate-400">
                        <span class="block text-xs font-semibold uppercase tracking-wider text-white">Working Hours</span>
                        <p class="mt-1 whitespace-pre-line">{{ $settings->working_hours }}</p>
                    </div>
                @endif
            </div>

            <div class="lg:col-span-2">
                <h3 class="text-sm font-semibold uppercase tracking-wider text-white">Quick Links</h3>
                <ul class="mt-5 space-y-3 text-sm">
                    <li><a href="/" class="text-slate-400 transition hover:text-primary-400">Home</a></li>
                    <li><a href="/#about" class="text-slate-400 transition hover:text-primary-400">About</a></li>
                    <li><a href="/#services" class="text-slate-400 transition hover:text-primary-400">Services</a></li>
                    <li><a href="/#maps-process" class="text-slate-400 transition hover:text-primary-400">Our Process</a></li>
                    <li><a href="/#team" class="text-slate-400 transition hover:text-primary-400">Team</a></li>
                    <li><a href="/#why-us" class="text-slate-400 transition hover:text-primary-400">Why Choose Us</a></li>
                    <li><a href="/#blog" class="text-slate-400 transition hover:text-primary-400">Blog</a></li>
                    <li><a href="/#faq" class="text-slate-400 transition hover:text-primary-400">FAQ</a></li>
                    <li><a href="/#contact" class="text-slate-400 transition hover:text-primary-400">Contact</a></li>
                </ul>
            </div>

            <div class="lg:col-span-3">
                <h3 class="text-sm font-semibold uppercase tracking-wider text-white">Services</h3>
                <ul class="mt-5 space-y-3 text-sm">
                    <li><a href="/#contact" class="text-slate-400 transition hover:text-primary-400">Google Business Profile Optimization</a></li>
                    <li><a href="/#maps-process" class="text-slate-400 transition hover:text-primary-400">Google Maps Ranking</a></li>
                    <li><a href="/#contact" class="text-slate-400 transition hover:text-primary-400">Local SEO</a></li>
                    <li><a href="/#contact" class="text-slate-400 transition hover:text-primary-400">Citation Management</a></li>
                    <li><a href="/#contact" class="text-slate-400 transition hover:text-primary-400">Review Management</a></li>
                    <li><a href="/#website-development" class="text-slate-400 transition hover:text-primary-400">Website Development</a></li>
                    <li><a href="/#pos-billing" class="text-slate-400 transition hover:text-primary-400">POS &amp; Billing System</a></li>
                </ul>
            </div>

            <div class="lg:col-span-3">
                <h3 class="text-sm font-semibold uppercase tracking-wider text-white">Contact Information</h3>
                <ul class="mt-5 space-y-4 text-sm">
                    <li>
                        <a href="tel:{{ $settings->phone ?? '+923266787997' }}" class="flex items-center gap-3 text-slate-300 transition hover:text-primary-400">
                            <span class="flex h-9 w-9 flex-none items-center justify-center rounded-lg bg-white/5 text-primary-400">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M5 4h3l2 5-2.5 1.5a11 11 0 0 0 5 5L14 13l5 2v3a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z" />
                                </svg>
                            </span>
                            {{ $settings->phone ?? '0326 6787997' }}
                        </a>
                    </li>
                    <li>
                        <a href="mailto:{{ $settings->email ?? 'contact@mapzoon.com' }}" class="flex items-center gap-3 text-slate-300 transition hover:text-primary-400">
                            <span class="flex h-9 w-9 flex-none items-center justify-center rounded-lg bg-white/5 text-primary-400">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="3" y="5" width="18" height="14" rx="2" />
                                    <path d="M3.5 6.5 12 13l8.5-6.5" />
                                </svg>
                            </span>
                            {{ $settings->email ?? 'contact@mapzoon.com' }}
                        </a>
                    </li>
                </ul>
                <a href="/#contact" class="mt-5 inline-flex items-center gap-2 rounded-xl bg-primary-500 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-600">
                    Get Free Audit
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 12h14M13 6l6 6-6 6" />
                    </svg>
                </a>
            </div>
        </div>

        @php
            $socialLinks = array_filter([
                'facebook_url' => $settings->facebook_url,
                'twitter_url' => $settings->twitter_url,
                'instagram_url' => $settings->instagram_url,
                'linkedin_url' => $settings->linkedin_url,
                'youtube_url' => $settings->youtube_url,
            ]);
            $socialIcons = [
                'facebook_url' => 'M14 9h2.5V6.5H14C12.067 6.5 10.5 8.067 10.5 10v2H8.5v2.5h2V19H13v-4.5h2.2l.3-2.5H13V10c0-.552.448-1 1-1Z',
                'twitter_url' => 'M4 4l16 16M20 4 4 20M4 4h5.5L20 20h-5.5L4 4Z',
                'instagram_url' => 'M7 4h10a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3Zm5 5a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm4.5-1.5h.01',
                'linkedin_url' => 'M6.5 9.5v9M6.5 6.5v.01M11 18.5v-5a2.5 2.5 0 0 1 5 0v5M11 13.5v5',
                'youtube_url' => 'M5 8.5A2.5 2.5 0 0 1 7.5 6h9A2.5 2.5 0 0 1 19 8.5v7a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 5 15.5v-7Zm5.5 1.25 4 2.25-4 2.25v-4.5Z',
            ];
        @endphp
        @if ($socialLinks)
            <div class="mt-10 flex justify-center gap-3 sm:justify-start">
                @foreach ($socialLinks as $key => $url)
                    <a href="{{ $url }}" target="_blank" rel="noopener" class="flex h-9 w-9 items-center justify-center rounded-lg bg-white/5 text-slate-400 transition hover:bg-white/10 hover:text-primary-400">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="{{ $socialIcons[$key] }}" />
                        </svg>
                    </a>
                @endforeach
            </div>
        @endif

        <div class="mt-10 flex flex-col items-center gap-3 border-t border-white/10 pt-6 text-center sm:flex-row sm:justify-between sm:text-left">
            <p class="text-sm text-slate-500">&copy; {{ now()->year }} {{ $settings->company_name ?? 'MAPZOON' }}. All rights reserved.</p>
            <p class="text-xs text-slate-500">Local SEO &middot; Google Maps Ranking &middot; Website Development &middot; POS &amp; Billing Systems</p>
        </div>
    </div>
</footer>
