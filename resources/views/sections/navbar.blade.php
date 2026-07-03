<?php $settings = \App\Models\SiteSetting::current(); ?>
@php
    $routeName = Route::currentRouteName();
    $companyRoutes = ['about', 'process', 'why-choose-us', 'testimonials', 'team', 'faq'];
    $isCompanyActive = in_array($routeName, $companyRoutes);

    $navLinks = [
        ['route' => 'services',       'label' => 'Services'],
        ['route' => 'pricing',        'label' => 'Pricing'],
        ['route' => 'case-studies',   'label' => 'Case Studies', 'active' => ['case-studies', 'case-studies.show']],
        ['route' => 'blog.index',     'label' => 'Blog'],
        ['route' => 'jobs',           'label' => 'Jobs'],
        ['route' => 'contact.page',   'label' => 'Contact'],
    ];

    $companyLinks = [
        ['route' => 'about',          'label' => 'About Us'],
        ['route' => 'process',        'label' => 'Our Process'],
        ['route' => 'why-choose-us',  'label' => 'Why Choose Us'],
        ['route' => 'testimonials',   'label' => 'Testimonials'],
        ['route' => 'team',           'label' => 'Team'],
        ['route' => 'faq',            'label' => 'FAQ'],

    ];

    $topbarPhone = $settings->phone ?? '+92 326 6787997';
    $topbarEmail = $settings->email ?? 'contact@mapzoon.com';
    $topbarHours = $settings->working_hours ? trim(strtok($settings->working_hours, "\r\n")) : 'Mon - Sat: 9:00 AM - 6:00 PM';
@endphp

{{-- Top Info Bar --}}
<div id="topbar" class="fixed inset-x-0 top-0 z-40 flex h-9 items-center bg-black px-4 text-xs font-medium text-slate-300 sm:px-6 lg:px-10">
    <div class="mx-auto flex w-full max-w-[1600px] items-center justify-center gap-4 sm:justify-between">
        <div class="flex items-center gap-4 sm:gap-6">
            <a href="tel:{{ $topbarPhone }}" class="flex items-center gap-1.5 whitespace-nowrap transition-colors hover:text-primary-400">
                <svg class="h-3.5 w-3.5 flex-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M5 4h3l2 5-2.5 1.5a11 11 0 0 0 5 5L14 13l5 2v3a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z" />
                </svg>
                <span>{{ $topbarPhone }}</span>
            </a>
            <a href="mailto:{{ $topbarEmail }}" class="flex items-center gap-1.5 whitespace-nowrap transition-colors hover:text-primary-400" aria-label="Email {{ $topbarEmail }}">
                <svg class="h-3.5 w-3.5 flex-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="3" y="5" width="18" height="14" rx="2" />
                    <path d="M3.5 6.5 12 13l8.5-6.5" />
                </svg>
                <span class="hidden sm:inline">{{ $topbarEmail }}</span>
            </a>
        </div>
        <div class="hidden items-center gap-1.5 whitespace-nowrap lg:flex">
            <svg class="h-3.5 w-3.5 flex-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="12" r="9" />
                <path d="M12 7v5l3 3" />
            </svg>
            <span>{{ $topbarHours }}</span>
        </div>
    </div>
</div>

<header id="site-navbar" class="reveal fixed inset-x-0 top-9 z-40 bg-white shadow-sm border-b border-slate-200/80 transition-all duration-300">
    <div class="mx-auto flex h-20 max-w-[1600px] items-center justify-between gap-4 px-6 lg:px-10">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex flex-none items-center gap-2.5 whitespace-nowrap">
            <img src="{{ $settings->logoDarkUrl() }}" alt="{{ $settings->company_name ?? 'MAPZOON' }}" class="h-12 w-auto" />
        </a>

        {{-- Desktop Nav --}}
        <nav class="hidden flex-1 items-center justify-center gap-1 xl:flex" aria-label="Primary">


            {{-- Other Nav Links --}}
            @foreach ($navLinks as $link)
                @php $linkActive = in_array($routeName, $link['active'] ?? [$link['route']]); @endphp
                <a href="{{ route($link['route']) }}"
                   class="group relative inline-flex items-center gap-1.5 whitespace-nowrap px-3 py-2 text-sm font-semibold transition-colors {{ $linkActive ? 'text-primary-600' : 'text-slate-700 hover:text-primary-600' }}">
                    {{ $link['label'] }}
                    @if ($link['route'] === 'jobs')
                        <span class="inline-flex items-center rounded-full bg-primary-500 px-1.5 py-0.5 text-[10px] font-bold leading-none text-white">Hiring</span>
                    @endif
                    <span class="absolute -bottom-0.5 left-0 h-0.5 w-full origin-left bg-primary-500 transition-transform duration-300 {{ $linkActive ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' }}" aria-hidden="true"></span>
                </a>
            @endforeach

            
            {{-- Company Dropdown --}}
            <div class="relative" id="company-dropdown-wrapper">
                <button type="button" id="company-dropdown-btn"
                        class="group relative flex items-center gap-1 whitespace-nowrap px-3 py-2 text-sm font-semibold transition-colors {{ $isCompanyActive ? 'text-primary-600' : 'text-slate-700 hover:text-primary-600' }}"
                        aria-haspopup="true" aria-expanded="false" aria-controls="company-dropdown">
                    Company
                    <svg id="company-chevron" class="h-4 w-4 transition-transform duration-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m6 9 6 6 6-6"/>
                    </svg>
                    <span class="absolute -bottom-0.5 left-0 h-0.5 w-full origin-left bg-primary-500 transition-transform duration-300 {{ $isCompanyActive ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' }}" aria-hidden="true"></span>
                </button>

                {{-- Dropdown Panel --}}
                <div id="company-dropdown"
                     class="hidden absolute left-0 top-[calc(100%+8px)] z-50 w-52 rounded-2xl bg-white py-2 shadow-xl ring-1 ring-slate-200/80"
                     role="menu" aria-label="Company menu">
                    @foreach ($companyLinks as $link)
                        <a href="{{ route($link['route']) }}"
                           role="menuitem"
                           class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-semibold transition-colors {{ $routeName === $link['route'] ? 'text-primary-600 bg-primary-50' : 'text-slate-700 hover:bg-slate-50 hover:text-primary-600' }}">
                            {{ $link['label'] }}
                            @if ($routeName === $link['route'])
                                <span class="ml-auto h-1.5 w-1.5 rounded-full bg-primary-500"></span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </nav>

        {{-- Right Side Actions --}}
        <div class="flex flex-none items-center gap-3 xl:gap-4">
            <a href="tel:{{ $topbarPhone }}" class="hidden items-center gap-2 whitespace-nowrap text-sm font-semibold text-slate-700 transition-colors hover:text-primary-600 xl:flex">
                <svg class="h-4 w-4 flex-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M5 4h3l2 5-2.5 1.5a11 11 0 0 0 5 5L14 13l5 2v3a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z" />
                </svg>
                {{ $topbarPhone }}
            </a>

            <a href="{{ route('contact.page') }}" class="hidden items-center justify-center gap-2 whitespace-nowrap rounded-none bg-primary-500 px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-primary-500/30 transition hover:-translate-y-0.5 hover:bg-primary-600 hover:shadow-lg hover:shadow-primary-500/40 xl:inline-flex">
                Get Free Audit
            </a>

            {{-- Mobile Menu Toggle --}}
            <button type="button" id="nav-menu-open" class="flex h-10 w-10 flex-none items-center justify-center rounded-none text-slate-700 transition hover:bg-slate-100 xl:hidden" aria-label="Open menu" aria-expanded="false" aria-controls="mobile-menu">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M4 7h16M4 12h16M4 17h16" />
                </svg>
            </button>
        </div>
    </div>
</header>

{{-- Mobile Menu --}}
<div id="mobile-menu" class="fixed inset-0 z-50 flex translate-x-full flex-col bg-white transition-transform duration-300 xl:hidden">
    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5">
            <img src="{{ $settings->logoDarkUrl() }}" alt="{{ $settings->company_name ?? 'MAPZOON' }}" class="h-12 w-auto" />
        </a>
        <button type="button" id="nav-menu-close" class="flex h-10 w-10 items-center justify-center rounded-none text-slate-700 transition hover:bg-slate-100" aria-label="Close menu">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M6 6l12 12M18 6 6 18" />
            </svg>
        </button>
    </div>

    <nav class="flex-1 overflow-y-auto px-6 py-6" aria-label="Mobile primary">
        <ul class="space-y-1">

            {{-- Other Links --}}
            @foreach ($navLinks as $link)
                @php $linkActive = in_array($routeName, $link['active'] ?? [$link['route']]); @endphp
                <li>
                    <a href="{{ route($link['route']) }}"
                       class="flex items-center gap-2 rounded-xl px-4 py-3 text-base font-semibold transition {{ $linkActive ? 'bg-primary-50 text-primary-600' : 'text-slate-800 hover:bg-slate-50 hover:text-primary-600' }}">
                        {{ $link['label'] }}
                        @if ($link['route'] === 'jobs')
                            <span class="inline-flex items-center rounded-full bg-primary-500 px-1.5 py-0.5 text-[10px] font-bold leading-none text-white">Hiring</span>
                        @endif
                    </a>
                </li>
            @endforeach
            
            {{-- Company collapsible --}}
            <li>
                <button type="button" id="mobile-company-btn"
                        class="flex w-full items-center justify-between rounded-none px-4 py-3 text-base font-semibold transition {{ $isCompanyActive ? 'bg-primary-50 text-primary-600' : 'text-slate-800 hover:bg-slate-50 hover:text-primary-600' }}"
                        aria-expanded="{{ $isCompanyActive ? 'true' : 'false' }}" aria-controls="mobile-company-menu">
                    Company
                    <svg id="mobile-company-chevron" class="h-4 w-4 transition-transform duration-200 {{ $isCompanyActive ? 'rotate-180' : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m6 9 6 6 6-6"/>
                    </svg>
                </button>
                <ul id="mobile-company-menu" class="{{ $isCompanyActive ? '' : 'hidden' }} mt-1 space-y-1 pl-4">
                    @foreach ($companyLinks as $link)
                        <li>
                            <a href="{{ route($link['route']) }}"
                               class="block rounded-xl px-4 py-2.5 text-sm font-semibold transition {{ $routeName === $link['route'] ? 'bg-primary-50 text-primary-600' : 'text-slate-700 hover:bg-slate-50 hover:text-primary-600' }}">
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>

        </ul>
    </nav>

    <div class="space-y-4 border-t border-slate-100 px-6 py-6">
        <a href="tel:{{ $topbarPhone }}" class="flex items-center justify-center gap-2 text-sm font-semibold text-slate-700">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M5 4h3l2 5-2.5 1.5a11 11 0 0 0 5 5L14 13l5 2v3a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z" />
            </svg>
            {{ $topbarPhone }}
        </a>
        <a href="{{ route('contact.page') }}" class="block w-full rounded-none bg-primary-500 px-5 py-3 text-center text-sm font-semibold text-white shadow-lg shadow-primary-500/30 transition hover:bg-primary-600">
            Get Free Audit
        </a>
    </div>
</div>
