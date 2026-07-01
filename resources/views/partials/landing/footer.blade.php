<?php $settings = \App\Models\SiteSetting::current(); ?>
<footer class="relative border-t border-white/10 bg-black pt-16 pb-8">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-12 lg:gap-8">

            <div class="sm:col-span-2 lg:col-span-4">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5">
                    <img src="{{ $settings->logoLightUrl() }}" alt="{{ $settings->company_name ?? 'MAPZOON' }}" class="h-12 w-auto" />
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
                    <li><a href="{{ url('/') }}" class="text-slate-400 transition hover:text-primary-400">Home</a></li>
                    <li><a href="{{ url('/#about') }}" class="text-slate-400 transition hover:text-primary-400">About</a></li>
                    <li><a href="{{ url('/#services') }}" class="text-slate-400 transition hover:text-primary-400">Services</a></li>
                    <li><a href="{{ url('/#maps-process') }}" class="text-slate-400 transition hover:text-primary-400">Our Process</a></li>
                    <li><a href="{{ url('/#team') }}" class="text-slate-400 transition hover:text-primary-400">Team</a></li>
                    <li><a href="{{ url('/#why-us') }}" class="text-slate-400 transition hover:text-primary-400">Why Choose Us</a></li>
                    <li><a href="{{ url('/#blog') }}" class="text-slate-400 transition hover:text-primary-400">Blog</a></li>
                    <li><a href="{{ url('/#faq') }}" class="text-slate-400 transition hover:text-primary-400">FAQ</a></li>
                    <li><a href="{{ url('/#contact') }}" class="text-slate-400 transition hover:text-primary-400">Contact</a></li>
                </ul>
            </div>

            <div class="lg:col-span-3">
                <h3 class="text-sm font-semibold uppercase tracking-wider text-white">Services</h3>
                <ul class="mt-5 space-y-3 text-sm">
                    <li><a href="{{ url('/#contact') }}" class="text-slate-400 transition hover:text-primary-400">Google Business Profile Optimization</a></li>
                    <li><a href="{{ url('/#maps-process') }}" class="text-slate-400 transition hover:text-primary-400">Google Maps Ranking</a></li>
                    <li><a href="{{ url('/#contact') }}" class="text-slate-400 transition hover:text-primary-400">Local SEO</a></li>
                    <li><a href="{{ url('/#contact') }}" class="text-slate-400 transition hover:text-primary-400">Citation Management</a></li>
                    <li><a href="{{ url('/#contact') }}" class="text-slate-400 transition hover:text-primary-400">Review Management</a></li>
                    <li><a href="{{ url('/#website-development') }}" class="text-slate-400 transition hover:text-primary-400">Website Development</a></li>
                    <li><a href="{{ url('/#pos-billing') }}" class="text-slate-400 transition hover:text-primary-400">POS &amp; Billing System</a></li>
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
                <a href="{{ url('/#contact') }}" class="mt-5 inline-flex items-center gap-2 rounded-xl bg-primary-500 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-600">
                    Get Free Audit
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 12h14M13 6l6 6-6 6" />
                    </svg>
                </a>
            </div>
        </div>

        @php
            $socials = [
                [
                    'key'   => 'facebook_url',
                    'url'   => $settings->facebook_url ?: '#',
                    'label' => 'Facebook',
                    'icon'  => 'M14 9h2.5V6.5H14C12.067 6.5 10.5 8.067 10.5 10v2H8.5v2.5h2V19H13v-4.5h2.2l.3-2.5H13V10c0-.552.448-1 1-1Z',
                    'fill'  => true,
                ],
                [
                    'key'   => 'instagram_url',
                    'url'   => $settings->instagram_url ?: '#',
                    'label' => 'Instagram',
                    'icon'  => 'M7 4h10a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3Zm5 5a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm4.5-1.5h.01',
                    'fill'  => false,
                ],
                [
                    'key'   => 'linkedin_url',
                    'url'   => $settings->linkedin_url ?: '#',
                    'label' => 'LinkedIn',
                    'icon'  => 'M6.5 9.5v9M6.5 6.5v.01M11 18.5v-5a2.5 2.5 0 0 1 5 0v5M11 13.5v5',
                    'fill'  => false,
                ],
                [
                    'key'   => 'youtube_url',
                    'url'   => $settings->youtube_url ?: '#',
                    'label' => 'YouTube',
                    'icon'  => 'M5 8.5A2.5 2.5 0 0 1 7.5 6h9A2.5 2.5 0 0 1 19 8.5v7a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 5 15.5v-7Zm5.5 1.25 4 2.25-4 2.25v-4.5Z',
                    'fill'  => false,
                ],
                [
                    'key'   => 'twitter_url',
                    'url'   => $settings->twitter_url ?: '#',
                    'label' => 'X (Twitter)',
                    'icon'  => 'M4 4h5.5L20 20h-5.5L4 4Zm0 16 6.5-6.5M20 4l-6.5 6.5',
                    'fill'  => false,
                ],
            ];
        @endphp
        <div class="mt-10 flex flex-wrap gap-3">
            <p class="w-full text-xs font-semibold uppercase tracking-wider text-slate-500">Follow Us</p>
            @foreach ($socials as $s)
                <a href="{{ $s['url'] }}" target="{{ $s['url'] !== '#' ? '_blank' : '_self' }}" rel="noopener noreferrer"
                   aria-label="{{ $s['label'] }}"
                   class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#00a656] text-white transition duration-200 hover:bg-[#008a47]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" @if($s['fill']) fill="currentColor" @else fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" @endif aria-hidden="true">
                        <path d="{{ $s['icon'] }}" />
                    </svg>
                </a>
            @endforeach
        </div>

    </div>

    <div class="mt-10 w-full">
        <img src="{{ asset('uploads/settings/mapzoon-footer-logo.png') }}" alt="{{ $settings->company_name ?? 'MAPZOON' }}" class="block h-auto w-full" />
    </div>

    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mt-6 flex flex-col items-center gap-3 border-t border-white/10 pt-6 text-center sm:flex-row sm:justify-between sm:text-left">
            <p class="text-sm text-slate-500">&copy; {{ now()->year }} {{ $settings->company_name ?? 'MAPZOON' }}. All rights reserved.</p>
            <p class="text-xs text-slate-500">Local SEO &middot; Google Maps Ranking &middot; Website Development &middot; POS &amp; Billing Systems</p>
        </div>
    </div>
</footer>
