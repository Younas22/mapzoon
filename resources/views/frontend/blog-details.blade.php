@extends('layouts.app')

@push('schema')
    @if ($post->seo?->custom_schema)
        <script type="application/ld+json">
        {!! $post->seo->custom_schema !!}
        </script>
    @endif
@endpush

@section('content')
    @include('sections.navbar')

    <article class="bg-white">
        <section class="relative overflow-hidden bg-slate-50 pt-32 pb-12 lg:pt-40 lg:pb-16">
            <div class="absolute left-1/2 top-0 -z-0 h-[480px] w-[800px] -translate-x-1/2 rounded-full bg-primary-200/30 blur-3xl" aria-hidden="true"></div>

            <div class="relative mx-auto max-w-4xl px-6 lg:px-8">
                <nav class="flex items-center gap-2 text-sm text-slate-500" aria-label="Breadcrumb">
                    <a href="{{ url('/') }}" class="transition hover:text-primary-600">Home</a>
                    <span aria-hidden="true">/</span>
                    <a href="{{ url('/#blog') }}" class="transition hover:text-primary-600">Blog</a>
                    <span aria-hidden="true">/</span>
                    <span class="text-slate-700">{{ $post->category?->name ?? 'Uncategorized' }}</span>
                </nav>

                <span class="mt-6 inline-flex rounded-full bg-primary-500 px-3 py-1 text-xs font-semibold text-white">
                    {{ $post->category?->name ?? 'Uncategorized' }}
                </span>

                <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl lg:text-5xl">
                    {{ $post->title }}
                </h1>

                <div class="mt-6 flex flex-wrap items-center gap-4">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-primary-100 text-sm font-bold text-primary-700">
                            {{ Illuminate\Support\Str::upper(Illuminate\Support\Str::substr($post->author?->name ?? 'M', 0, 1)) }}
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">{{ $post->author?->name ?? 'MAPZOON Team' }}</p>
                            <p class="text-xs text-slate-500">{{ $post->author?->designation ?? '' }}</p>
                        </div>
                    </div>
                    <span class="h-8 w-px bg-slate-200" aria-hidden="true"></span>
                    <span class="text-sm text-slate-500">{{ $post->published_at?->format('F j, Y') }}</span>
                    <span class="h-8 w-px bg-slate-200" aria-hidden="true"></span>
                    <span class="text-sm text-slate-500">{{ $post->readingTime() }} min read</span>
                </div>

                <div class="mt-10 flex aspect-[16/7] items-center justify-center overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-primary-800 shadow-xl">
                    @if ($post->featuredImageUrl())
                        <img src="{{ $post->featuredImageUrl() }}" alt="{{ $post->title }}" class="h-full w-full object-cover">
                    @else
                        <svg class="h-20 w-20 text-white/20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="6" width="18" height="13" rx="2" />
                            <circle cx="12" cy="12.5" r="2.5" />
                        </svg>
                    @endif
                </div>
            </div>
        </section>

        <div class="mx-auto max-w-7xl px-6 py-16 lg:px-8 lg:py-20">
            <div class="grid grid-cols-1 gap-12 lg:grid-cols-3 lg:gap-16">

                <div class="lg:col-span-2">
                    <div class="prose prose-slate max-w-none prose-headings:font-extrabold prose-headings:tracking-tight prose-headings:text-slate-900 prose-a:text-primary-600 prose-img:rounded-2xl prose-blockquote:border-primary-500 prose-blockquote:bg-primary-50 prose-blockquote:not-italic">
                        {!! $post->content !!}
                    </div>

                    @if ($post->tags->isNotEmpty())
                        <div class="mt-8 flex flex-wrap items-center gap-2">
                            @foreach ($post->tags as $tag)
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">#{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    @endif

                    @if ($post->faqs->isNotEmpty())
                        <div class="mt-12 border-t border-slate-100 pt-8">
                            <h3 class="text-xl font-extrabold tracking-tight text-slate-900">Frequently Asked Questions</h3>
                            <div class="mt-6 space-y-4">
                                @foreach ($post->faqs as $faq)
                                    <div class="rounded-2xl border border-slate-200 p-5">
                                        <p class="font-semibold text-slate-900">{{ $faq->question }}</p>
                                        <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $faq->answer }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mt-12 flex flex-wrap items-center gap-3 border-t border-slate-100 pt-8">
                        <span class="text-sm font-semibold text-slate-700">Share this article:</span>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" target="_blank" rel="noopener noreferrer" class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-slate-600 transition hover:bg-primary-50 hover:text-primary-600" aria-label="Share on X (Twitter)">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.9 2H22l-7.1 8.1L23 22h-6.9l-5.4-7-6.2 7H1.3l7.6-8.7L1 2h7l4.9 6.4L18.9 2z" /></svg>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer" class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-slate-600 transition hover:bg-primary-50 hover:text-primary-600" aria-label="Share on Facebook">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M13.5 21v-7.2h2.4l.4-2.8h-2.8V9.1c0-.8.2-1.4 1.4-1.4h1.5V5.2c-.3 0-1.2-.1-2.2-.1-2.2 0-3.7 1.3-3.7 3.8V11H8v2.8h2.5V21h3z" /></svg>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer" class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-slate-600 transition hover:bg-primary-50 hover:text-primary-600" aria-label="Share on LinkedIn">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4.98 3.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5zM3 9h4v12H3V9zm6.5 0h3.8v1.7h.05c.53-1 1.84-2.06 3.78-2.06 4.04 0 4.78 2.66 4.78 6.12V21h-4v-5.5c0-1.31-.02-3-1.83-3-1.83 0-2.1 1.43-2.1 2.9V21h-4V9z" /></svg>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($post->title.' '.url()->current()) }}" target="_blank" rel="noopener noreferrer" class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-slate-600 transition hover:bg-primary-50 hover:text-primary-600" aria-label="Share on WhatsApp">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5.1-1.3A10 10 0 1 0 12 2zm0 18.2c-1.6 0-3.1-.4-4.4-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2zm4.5-6.1c-.2-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1-.2.2-.6.8-.8 1-.1.2-.3.2-.5.1-1.3-.6-2.2-1.1-3.1-2.5-.2-.4.2-.4.5-.8.1-.1.1-.3 0-.4-.1-.1-.5-1.3-.7-1.7-.2-.4-.4-.4-.6-.4h-.5c-.2 0-.4.1-.6.3-.2.2-.8.8-.8 1.9 0 1.1.8 2.2.9 2.4.1.2 1.8 2.8 4.4 3.8 2.1.8 2.5.6 3 .6.4 0 1.5-.6 1.7-1.2.2-.6.2-1.1.1-1.2-.1-.1-.2-.2-.4-.3z" /></svg>
                        </a>
                    </div>

                    <div class="mt-8 flex flex-col gap-5 rounded-3xl border border-slate-200 bg-slate-50 p-6 sm:flex-row sm:items-center">
                        <span class="flex h-16 w-16 flex-none items-center justify-center rounded-full bg-gradient-to-br from-primary-400 to-primary-600 text-xl font-bold text-white">
                            {{ Illuminate\Support\Str::upper(Illuminate\Support\Str::substr($post->author?->name ?? 'M', 0, 1)) }}
                        </span>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-primary-600">Written by</p>
                            <h3 class="mt-1 text-lg font-bold text-slate-900">{{ $post->author?->name ?? 'MAPZOON Team' }}</h3>
                            <p class="text-sm text-slate-600">{{ $post->author?->designation ?? 'Contributor' }} at MAPZOON</p>
                        </div>
                        @if ($post->author?->email)
                            <a href="mailto:{{ $post->author->email }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:text-primary-600 sm:ml-auto">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="3" y="5" width="18" height="14" rx="2" />
                                    <path d="M3.5 6.5 12 13l8.5-6.5" />
                                </svg>
                                Email
                            </a>
                        @endif
                    </div>

                    @if ($related->isNotEmpty())
                        <div class="mt-14">
                            <h3 class="text-xl font-extrabold tracking-tight text-slate-900">Related Articles</h3>
                            <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                                @foreach ($related as $item)
                                    <a href="{{ route('blog.show', $item->slug) }}" class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                                        <div class="flex aspect-video items-center justify-center overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-primary-800">
                                            @if ($item->featuredImageUrl())
                                                <img src="{{ $item->featuredImageUrl() }}" alt="{{ $item->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-110">
                                            @else
                                                <svg class="h-10 w-10 text-white/20 transition duration-500 group-hover:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <rect x="3" y="6" width="18" height="13" rx="2" />
                                                    <circle cx="12" cy="12.5" r="2.5" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="p-4">
                                            <p class="text-xs font-semibold text-primary-600">{{ $item->category?->name ?? 'Uncategorized' }}</p>
                                            <p class="mt-1 text-sm font-bold text-slate-900 transition group-hover:text-primary-600">{{ $item->title }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mt-12 grid grid-cols-1 gap-4 border-t border-slate-200 pt-8 sm:grid-cols-2">
                        @if ($previous)
                            <a href="{{ route('blog.show', $previous->slug) }}" class="group rounded-2xl border border-slate-200 p-5 transition hover:border-primary-200 hover:shadow-md">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">&larr; Previous Post</p>
                                <p class="mt-1 font-bold text-slate-900 transition group-hover:text-primary-600">{{ $previous->title }}</p>
                            </a>
                        @else
                            <span></span>
                        @endif

                        @if ($next)
                            <a href="{{ route('blog.show', $next->slug) }}" class="group rounded-2xl border border-slate-200 p-5 text-right transition hover:border-primary-200 hover:shadow-md">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Next Post &rarr;</p>
                                <p class="mt-1 font-bold text-slate-900 transition group-hover:text-primary-600">{{ $next->title }}</p>
                            </a>
                        @endif
                    </div>
                </div>

                <aside class="lg:col-span-1">
                    <div class="space-y-8 lg:sticky lg:top-28">
                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-900">Recent Posts</h3>
                            <ul class="mt-5 space-y-4">
                                @foreach ($recentPosts as $item)
                                    <li>
                                        <a href="{{ route('blog.show', $item->slug) }}" class="group flex items-center gap-3">
                                            <span class="flex h-12 w-12 flex-none items-center justify-center overflow-hidden rounded-xl bg-gradient-to-br from-slate-800 to-primary-800 text-white">
                                                @if ($item->featuredImageUrl())
                                                    <img src="{{ $item->featuredImageUrl() }}" alt="{{ $item->title }}" class="h-full w-full object-cover">
                                                @else
                                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                        <rect x="3" y="6" width="18" height="13" rx="2" />
                                                        <circle cx="12" cy="12.5" r="2.5" />
                                                    </svg>
                                                @endif
                                            </span>
                                            <span>
                                                <span class="block text-sm font-semibold text-slate-800 transition group-hover:text-primary-600">{{ \Illuminate\Support\Str::limit($item->title, 48) }}</span>
                                                <span class="block text-xs text-slate-500">{{ $item->published_at?->format('M j, Y') }}</span>
                                            </span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-900">Categories</h3>
                            <div class="mt-5 flex flex-wrap gap-2">
                                @foreach ($categories as $category)
                                    <span class="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700">{{ $category->name }}</span>
                                @endforeach
                            </div>
                        </div>

                        <div class="rounded-3xl bg-gradient-to-br from-slate-900 to-slate-800 p-6 text-white shadow-lg">
                            <h3 class="text-sm font-semibold uppercase tracking-wider text-primary-400">Newsletter</h3>
                            <p class="mt-3 text-sm leading-relaxed text-slate-300">
                                Get our latest Local SEO tips and Google Maps ranking insights straight to your inbox.
                            </p>

                            @if (session('newsletter_success'))
                                <div class="mt-4 rounded-xl bg-primary-500/15 px-4 py-3 text-sm font-medium text-primary-300">
                                    {{ session('newsletter_success') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('newsletter.subscribe') }}" class="mt-4 space-y-3">
                                @csrf
                                <input type="email" name="email" required placeholder="you@business.com" value="{{ old('email') }}"
                                    class="w-full rounded-xl border border-white/10 bg-white/10 px-4 py-2.5 text-sm text-white placeholder:text-slate-400 focus:border-primary-400 focus:outline-none focus:ring-2 focus:ring-primary-400/30">
                                @error('email')
                                    <p class="text-xs text-red-400">{{ $message }}</p>
                                @enderror
                                <button type="submit" class="w-full rounded-xl bg-primary-500 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-600">
                                    Subscribe
                                </button>
                            </form>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </article>

    @include('partials.landing.footer')
@endsection
