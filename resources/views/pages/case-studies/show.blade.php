@extends('layouts.app')

@section('title', $title)
@section('description', $description)

@section('content')
    @include('sections.navbar')

    <div class="pt-[7.25rem]">
        <article class="bg-white">

            {{-- Hero --}}
            <div class="relative overflow-hidden bg-slate-900 py-16 lg:py-20">
                <div class="absolute inset-0 overflow-hidden">
                    @if ($caseStudy->imageUrl())
                        <img src="{{ $caseStudy->imageUrl() }}" alt="{{ $caseStudy->title }}" class="h-full w-full object-cover opacity-20">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-b from-slate-900/85 to-slate-900/95"></div>
                </div>

                <div class="relative mx-auto max-w-4xl px-6 lg:px-8">
                    <a href="{{ route('case-studies') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-primary-400 transition hover:text-primary-300">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                        All Case Studies
                    </a>
                    <p class="mt-5 text-sm font-semibold uppercase tracking-wider text-primary-400">Case Study</p>
                    <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-white sm:text-4xl lg:text-5xl">
                        {{ $caseStudy->title }}
                    </h1>
                </div>
            </div>

            <div class="mx-auto max-w-7xl px-6 py-14 lg:px-8 lg:py-20">
                <div class="grid grid-cols-1 gap-10 lg:grid-cols-3 lg:gap-12">

                    {{-- Main content --}}
                    <div class="lg:col-span-2 space-y-10">

                        {{-- Gallery: feature image + screenshots --}}
                        @php
                            $galleryImages = collect([$caseStudy->imageUrl()])
                                ->filter()
                                ->merge($caseStudy->screenshotUrls())
                                ->values();
                        @endphp

                        @if ($galleryImages->isNotEmpty())
                            <div class="reveal">
                                <h2 class="mb-4 text-lg font-bold text-slate-900">Gallery</h2>

                                <div class="flex flex-col-reverse gap-3 sm:flex-row" data-gallery data-gallery-images="{{ $galleryImages->toJson() }}">
                                    @if ($galleryImages->count() > 1)
                                        <div class="flex flex-none gap-3 overflow-x-auto pb-1 sm:w-24 sm:flex-col sm:overflow-x-visible sm:overflow-y-auto sm:pb-0 sm:max-h-[420px]">
                                            @foreach ($galleryImages as $index => $url)
                                                <button type="button" data-gallery-thumb data-index="{{ $index }}"
                                                        class="aspect-video w-20 flex-none overflow-hidden rounded-none border-2 {{ $index === 0 ? 'border-primary-500' : 'border-transparent' }} transition hover:border-primary-300 sm:w-full">
                                                    <img src="{{ $url }}" alt="Preview {{ $index + 1 }}" class="h-full w-full object-cover">
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif

                                    <button type="button" data-gallery-main data-index="0"
                                            class="group relative aspect-video flex-1 overflow-hidden rounded-none shadow-lg">
                                        <img data-gallery-main-img src="{{ $galleryImages->first() }}" alt="{{ $caseStudy->title }}" class="h-full w-full object-cover">
                                        <span class="absolute inset-0 flex items-center justify-center bg-slate-900/0 transition group-hover:bg-slate-900/25">
                                            <span class="flex h-12 w-12 items-center justify-center rounded-none bg-white/90 opacity-0 shadow-lg transition group-hover:opacity-100">
                                                <svg class="h-5 w-5 text-slate-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/></svg>
                                            </span>
                                        </span>
                                    </button>
                                </div>
                            </div>

                            {{-- Lightbox --}}
                            <div id="screenshot-modal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-slate-950/90 p-4 sm:p-8" role="dialog" aria-modal="true" aria-label="Screenshot viewer">
                                <button type="button" id="screenshot-modal-close" class="absolute right-4 top-4 flex h-10 w-10 items-center justify-center rounded-none bg-white/10 text-white transition hover:bg-white/20 sm:right-6 sm:top-6" aria-label="Close">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
                                </button>

                                @if ($galleryImages->count() > 1)
                                    <button type="button" id="screenshot-modal-prev" class="absolute left-2 top-1/2 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-none bg-white/10 text-white transition hover:bg-white/20 sm:left-6" aria-label="Previous image">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                                    </button>
                                    <button type="button" id="screenshot-modal-next" class="absolute right-2 top-1/2 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-none bg-white/10 text-white transition hover:bg-white/20 sm:right-6" aria-label="Next image">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                                    </button>
                                @endif

                                <img id="screenshot-modal-img" src="" alt="{{ $caseStudy->title }}" class="max-h-[85vh] max-w-full rounded-xl object-contain shadow-2xl">
                            </div>
                        @endif

                        {{-- Description --}}
                        <div class="reveal rounded-2xl border border-slate-200 p-6 sm:p-8">
                            <h2 class="text-lg font-bold text-slate-900">Overview</h2>
                            <p class="mt-3 text-base leading-relaxed text-slate-600 sm:text-lg">{{ $caseStudy->description }}</p>
                        </div>

                        {{-- Client Review --}}
                        @if ($caseStudy->owner_review || $caseStudy->youtubeEmbedUrl())
                            <div class="reveal rounded-2xl border border-primary-100 bg-primary-50/60 p-6 sm:p-8">
                                <h2 class="text-lg font-bold text-slate-900">
                                    {{ $caseStudy->owner_name ? 'Hear It From '.$caseStudy->owner_name : 'Client Review' }}
                                </h2>

                                @if ($caseStudy->youtubeEmbedUrl())
                                    <div class="mt-5 aspect-video overflow-hidden rounded-2xl shadow-lg">
                                        <iframe class="h-full w-full"
                                                src="{{ $caseStudy->youtubeEmbedUrl() }}"
                                                title="{{ $caseStudy->owner_name ?? $caseStudy->title }} — Video Review"
                                                frameborder="0"
                                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                                allowfullscreen></iframe>
                                    </div>
                                @endif

                                @if ($caseStudy->owner_review)
                                    <div class="mt-6 flex gap-1">
                                        @for ($i = 0; $i < 5; $i++)
                                            <svg class="h-5 w-5 text-amber-400" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                        @endfor
                                    </div>
                                    <blockquote class="mt-3 text-base italic leading-relaxed text-slate-700 sm:text-lg">
                                        "{{ $caseStudy->owner_review }}"
                                    </blockquote>
                                @endif

                                @if ($caseStudy->owner_name)
                                    <div class="mt-5 flex items-center gap-3">
                                        @if ($caseStudy->ownerPhotoUrl())
                                            <img src="{{ $caseStudy->ownerPhotoUrl() }}" alt="{{ $caseStudy->owner_name }}" class="h-10 w-10 flex-none rounded-full object-cover">
                                        @else
                                            <div class="flex h-10 w-10 flex-none items-center justify-center rounded-full bg-primary-200 text-sm font-bold text-primary-700">
                                                {{ strtoupper(substr($caseStudy->owner_name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-semibold text-slate-900">{{ $caseStudy->owner_name }}</p>
                                            <p class="text-xs text-slate-500">{{ $caseStudy->ownerRoleLabel() }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                    </div>

                    {{-- Sidebar --}}
                    <aside class="lg:sticky lg:top-24 lg:h-fit">
                        <div class="reveal rounded-2xl border border-slate-200 p-6 shadow-sm">
                            <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-500">Project Snapshot</h2>

                            @if ($caseStudy->owner_name)
                                <div class="mt-4 flex items-center gap-3 border-b border-slate-100 pb-4">
                                    @if ($caseStudy->ownerPhotoUrl())
                                        <img src="{{ $caseStudy->ownerPhotoUrl() }}" alt="{{ $caseStudy->owner_name }}" class="h-11 w-11 flex-none rounded-full object-cover">
                                    @else
                                        <div class="flex h-11 w-11 flex-none items-center justify-center rounded-full bg-primary-100 text-base font-bold text-primary-700">
                                            {{ strtoupper(substr($caseStudy->owner_name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-bold text-slate-900">{{ $caseStudy->owner_name }}</p>
                                        <p class="text-xs text-slate-500">{{ $caseStudy->ownerRoleLabel() }}</p>
                                    </div>
                                </div>
                            @endif

                            <div class="mt-4 space-y-2">
                                @if ($caseStudy->gmb_link)
                                    <a href="{{ $caseStudy->gmb_link }}" target="_blank" rel="noopener noreferrer"
                                       class="flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-primary-200 hover:bg-primary-50/60">
                                        <svg class="h-5 w-5 flex-none text-green-500" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                                        <span class="flex-1">View on Google Maps</span>
                                        <svg class="h-4 w-4 flex-none text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17 17 7M7 7h10v10"/></svg>
                                    </a>
                                @endif

                                @if ($caseStudy->website_link)
                                    <a href="{{ $caseStudy->website_link }}" target="_blank" rel="noopener noreferrer"
                                       class="flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-primary-200 hover:bg-primary-50/60">
                                        <svg class="h-5 w-5 flex-none text-primary-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                                        <span class="flex-1">Visit Website</span>
                                        <svg class="h-4 w-4 flex-none text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17 17 7M7 7h10v10"/></svg>
                                    </a>
                                @endif
                            </div>

                            <a href="{{ route('contact.page') }}"
                               class="mt-5 flex items-center justify-center gap-2 rounded-none bg-primary-500 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-primary-500/30 transition hover:bg-primary-600">
                                Get Similar Results
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" /></svg>
                            </a>
                        </div>
                    </aside>

                </div>
            </div>

            {{-- Other Case Studies --}}
            @if ($others->isNotEmpty())
                <div class="bg-slate-50 py-16 lg:py-20">
                    <div class="mx-auto max-w-7xl px-6 lg:px-8">
                        <h2 class="reveal mb-8 text-xl font-bold text-slate-900">More Case Studies</h2>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($others as $other)
                                <a href="{{ route('case-studies.show', $other->slug) }}"
                                   class="reveal group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                                    <div class="aspect-video w-full overflow-hidden bg-slate-100">
                                        @if ($other->imageUrl())
                                            <img src="{{ $other->imageUrl() }}" alt="{{ $other->title }}" class="h-full w-full object-cover transition group-hover:scale-105">
                                        @endif
                                    </div>
                                    <div class="p-5">
                                        <h3 class="font-bold text-slate-900 group-hover:text-primary-600 transition-colors">{{ $other->title }}</h3>
                                        <p class="mt-1 text-sm text-slate-500 line-clamp-2">{{ $other->description }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

        </article>
    </div>

    @include('partials.landing.footer')
    @include('partials.landing.quote-modal')
@endsection
