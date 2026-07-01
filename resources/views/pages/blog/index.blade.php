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
                    <p class="text-sm font-semibold uppercase tracking-wider text-primary-600">MAPZOON Blog</p>
                    <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl lg:text-5xl">
                        Insights & Tips
                    </h1>
                    <p class="mt-5 text-base text-slate-600 sm:text-lg">
                        Google Maps SEO, local business growth, website tips, and more — straight from our team of experts.
                    </p>
                </div>

                {{-- Categories filter --}}
                @if ($categories->isNotEmpty())
                    <div class="reveal mt-8 flex flex-wrap justify-center gap-2">
                        <a href="{{ route('blog.index') }}"
                           class="rounded-full px-4 py-1.5 text-sm font-semibold transition {{ ! request('category') ? 'bg-primary-500 text-white' : 'bg-slate-100 text-slate-700 hover:bg-primary-50 hover:text-primary-600' }}">
                            All
                        </a>
                        @foreach ($categories as $category)
                            <a href="{{ route('blog.index', ['category' => $category->slug]) }}"
                               class="rounded-full px-4 py-1.5 text-sm font-semibold transition {{ request('category') === $category->slug ? 'bg-primary-500 text-white' : 'bg-slate-100 text-slate-700 hover:bg-primary-50 hover:text-primary-600' }}">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                @endif

                @if ($posts->isEmpty())
                    <div class="mt-16 text-center">
                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-primary-50">
                            <svg class="h-10 w-10 text-primary-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/></svg>
                        </div>
                        <h3 class="mt-4 text-lg font-semibold text-slate-900">No posts yet</h3>
                        <p class="mt-1 text-sm text-slate-500">We're working on some great content. Check back soon!</p>
                    </div>
                @else
                    <div class="mt-12 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($posts as $post)
                            <a href="{{ route('blog.show', $post->slug) }}"
                               class="reveal group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">

                                @if ($post->featuredImageUrl())
                                    <div class="aspect-video w-full overflow-hidden bg-slate-100">
                                        <img src="{{ $post->featuredImageUrl() }}"
                                             alt="{{ $post->title }}"
                                             class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                    </div>
                                @endif

                                <div class="flex flex-1 flex-col p-6">
                                    @if ($post->category)
                                        <span class="text-xs font-semibold uppercase tracking-wider text-primary-600">{{ $post->category->name }}</span>
                                    @endif
                                    <h2 class="mt-2 flex-1 text-base font-bold text-slate-900 group-hover:text-primary-600 transition-colors leading-snug">
                                        {{ $post->title }}
                                    </h2>
                                    @if ($post->excerpt)
                                        <p class="mt-2 text-sm text-slate-500 line-clamp-2">{{ $post->excerpt }}</p>
                                    @endif
                                    <div class="mt-4 flex items-center justify-between text-xs text-slate-400">
                                        <span>{{ $post->published_at?->format('M d, Y') }}</span>
                                        <span>{{ $post->readingTime() }} min read</span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if ($posts->hasPages())
                        <div class="mt-12 flex justify-center">
                            {{ $posts->links() }}
                        </div>
                    @endif
                @endif

            </div>
        </section>
    </div>

    @include('partials.landing.footer')
    @include('partials.landing.quote-modal')
@endsection
