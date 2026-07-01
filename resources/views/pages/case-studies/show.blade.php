@extends('layouts.app')

@section('title', $title)
@section('description', $description)

@section('content')
    @include('sections.navbar')

    <div class="pt-20">
        <article class="bg-white">

            {{-- Hero --}}
            <div class="relative bg-slate-900 py-20 lg:py-28">
                <div class="absolute inset-0 overflow-hidden">
                    @if ($caseStudy->imageUrl())
                        <img src="{{ $caseStudy->imageUrl() }}" alt="{{ $caseStudy->title }}" class="h-full w-full object-cover opacity-20">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-b from-slate-900/80 to-slate-900/95"></div>
                </div>

                <div class="relative mx-auto max-w-4xl px-6 lg:px-8">
                    <a href="{{ route('case-studies') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-primary-400 transition hover:text-primary-300">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                        All Case Studies
                    </a>
                    <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-white sm:text-4xl lg:text-5xl">
                        {{ $caseStudy->title }}
                    </h1>

                    {{-- Action Buttons --}}
                    <div class="mt-6 flex flex-wrap gap-3">
                        @if ($caseStudy->gmb_link)
                            <a href="{{ $caseStudy->gmb_link }}" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-2 rounded-xl bg-green-500 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-green-600">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                                View on Google Maps
                            </a>
                        @endif

                        @if ($caseStudy->website_link)
                            <a href="{{ $caseStudy->website_link }}" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-2 rounded-xl bg-white/10 px-5 py-2.5 text-sm font-semibold text-white ring-1 ring-white/20 transition hover:bg-white/20">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                                Visit Website
                            </a>
                        @endif

                        <a href="{{ url('/#contact') }}"
                           class="inline-flex items-center gap-2 rounded-xl bg-primary-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-primary-500/30 transition hover:bg-primary-600">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 4h3l2 5-2.5 1.5a11 11 0 0 0 5 5L14 13l5 2v3a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/></svg>
                            Contact Us
                        </a>
                    </div>
                </div>
            </div>

            <div class="mx-auto max-w-4xl px-6 py-16 lg:px-8 lg:py-24">

                {{-- Description --}}
                <div class="reveal prose prose-slate prose-lg max-w-none">
                    <p class="text-base leading-relaxed text-slate-700 sm:text-lg">{{ $caseStudy->description }}</p>
                </div>

                {{-- Main Image --}}
                @if ($caseStudy->imageUrl())
                    <div class="reveal mt-10 overflow-hidden rounded-2xl shadow-lg">
                        <img src="{{ $caseStudy->imageUrl() }}" alt="{{ $caseStudy->title }}" class="w-full object-cover">
                    </div>
                @endif

                {{-- Video --}}
                @if ($caseStudy->youtubeEmbedUrl())
                    <div class="reveal mt-12">
                        <h2 class="mb-4 text-xl font-bold text-slate-900">Project Video</h2>
                        <div class="overflow-hidden rounded-2xl shadow-lg" style="aspect-ratio: 16/9;">
                            <iframe class="h-full w-full"
                                    src="{{ $caseStudy->youtubeEmbedUrl() }}"
                                    title="{{ $caseStudy->title }} — Video"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                        </div>
                    </div>
                @endif

                {{-- Screenshots --}}
                @if (! empty($caseStudy->screenshotUrls()))
                    <div class="reveal mt-12">
                        <h2 class="mb-6 text-xl font-bold text-slate-900">Screenshots</h2>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            @foreach ($caseStudy->screenshotUrls() as $screenshot)
                                <div class="overflow-hidden rounded-xl shadow-sm border border-slate-200">
                                    <img src="{{ $screenshot }}"
                                         alt="Screenshot {{ $loop->iteration }}"
                                         class="w-full object-cover">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Owner Review --}}
                @if ($caseStudy->owner_review)
                    <div class="reveal mt-12 rounded-2xl bg-primary-50 border border-primary-100 p-8">
                        <div class="flex gap-1 mb-4">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="h-5 w-5 text-amber-400" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            @endfor
                        </div>
                        <blockquote class="text-base italic text-slate-700 sm:text-lg">
                            "{{ $caseStudy->owner_review }}"
                        </blockquote>
                        @if ($caseStudy->owner_name)
                            <div class="mt-4 flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary-200 text-sm font-bold text-primary-700">
                                    {{ strtoupper(substr($caseStudy->owner_name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">{{ $caseStudy->owner_name }}</p>
                                    <p class="text-xs text-slate-500">Business Owner</p>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

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
