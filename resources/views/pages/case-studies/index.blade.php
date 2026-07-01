@extends('layouts.app')

@section('title', $title)
@section('description', $description)

@section('content')
    @include('sections.navbar')

    <div class="pt-20">
        <section class="relative bg-white py-20 lg:py-28">
            <div class="absolute left-1/2 top-0 -z-10 h-[500px] w-[900px] -translate-x-1/2 rounded-full bg-primary-100/40 blur-3xl" aria-hidden="true"></div>

            <div class="mx-auto max-w-7xl px-6 lg:px-8">

                <div class="reveal mx-auto max-w-2xl text-center">
                    <p class="text-sm font-semibold uppercase tracking-wider text-primary-600">Real Results</p>
                    <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl lg:text-5xl">
                        Case Studies
                    </h1>
                    <p class="mt-5 text-base text-slate-600 sm:text-lg">
                        See how we helped local businesses rank higher on Google Maps, get more calls, and grow their revenue.
                    </p>
                </div>

                @if ($caseStudies->isEmpty())
                    <div class="mt-16 text-center">
                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-primary-50">
                            <svg class="h-10 w-10 text-primary-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="mt-4 text-lg font-semibold text-slate-900">Case studies coming soon</h3>
                        <p class="mt-1 text-sm text-slate-500">We're documenting our client success stories. Check back soon.</p>
                        <a href="{{ url('/#contact') }}" class="mt-5 inline-flex items-center gap-2 rounded-xl bg-primary-500 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-600">
                            Work With Us
                        </a>
                    </div>
                @else
                    <div class="mt-14 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($caseStudies as $study)
                            <a href="{{ route('case-studies.show', $study->slug) }}"
                               class="reveal group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">

                                {{-- Image --}}
                                <div class="aspect-video w-full overflow-hidden bg-slate-100">
                                    @if ($study->imageUrl())
                                        <img src="{{ $study->imageUrl() }}"
                                             alt="{{ $study->title }}"
                                             class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                    @else
                                        <div class="flex h-full w-full items-center justify-center">
                                            <svg class="h-12 w-12 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                        </div>
                                    @endif
                                </div>

                                {{-- Content --}}
                                <div class="flex flex-1 flex-col p-6">
                                    <h2 class="text-lg font-bold text-slate-900 group-hover:text-primary-600 transition-colors">{{ $study->title }}</h2>
                                    <p class="mt-2 flex-1 text-sm text-slate-600 line-clamp-3">{{ $study->description }}</p>

                                    <div class="mt-4 flex flex-wrap items-center gap-2">
                                        @if ($study->gmb_link)
                                            <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-700">
                                                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                                                GMB Listed
                                            </span>
                                        @endif
                                        @if ($study->owner_name)
                                            <span class="text-xs text-slate-500">by {{ $study->owner_name }}</span>
                                        @endif
                                    </div>

                                    <div class="mt-4 flex items-center gap-1 text-sm font-semibold text-primary-600">
                                        View Case Study
                                        <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif

                {{-- CTA --}}
                <div class="reveal mt-16 rounded-2xl bg-primary-600 p-8 text-center text-white lg:p-12">
                    <h3 class="text-2xl font-bold">Ready to be our next success story?</h3>
                    <p class="mt-2 text-primary-200">Let's get your business ranking higher on Google Maps.</p>
                    <a href="{{ url('/#contact') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-white px-7 py-3.5 text-sm font-semibold text-primary-600 shadow-lg transition hover:-translate-y-0.5 hover:bg-primary-50">
                        Get a Free Audit
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                </div>

            </div>
        </section>
    </div>

    @include('partials.landing.footer')
    @include('partials.landing.quote-modal')
@endsection
