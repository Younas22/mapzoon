<section id="blog" class="relative bg-white py-20 lg:py-28">
    <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-primary-300 to-transparent" aria-hidden="true"></div>

    <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
        <div class="reveal mx-auto max-w-2xl text-center">
            <p class="text-sm font-semibold uppercase tracking-wider text-primary-600">From The Blog</p>
            <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                Latest Insights &amp; Local SEO Tips
            </h2>
        </div>

        <div class="mt-14 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach (array_slice(config('blog.posts'), 0, 3) as $i => $post)
                <article class="reveal reveal-delay-{{ $i + 1 }} group overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-lg transition duration-300 hover:-translate-y-1.5 hover:shadow-2xl">
                    <a href="{{ route('blog.show', $post['slug']) }}" class="relative block aspect-video overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-primary-800 transition duration-500 group-hover:scale-105"></div>
                        <div class="absolute inset-0 flex items-center justify-center text-white/20">
                            @switch($post['icon'])
                                @case('map-pin')
                                    <svg class="h-16 w-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M12 21s-7-7.58-7-12a7 7 0 1 1 14 0c0 4.42-7 12-7 12z" />
                                        <circle cx="12" cy="9" r="2.5" />
                                    </svg>
                                    @break
                                @case('profile')
                                    <svg class="h-16 w-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <rect x="4" y="5" width="16" height="14" rx="2" />
                                        <path d="M8 9h8M8 13h5" />
                                    </svg>
                                    @break
                                @default
                                    <svg class="h-16 w-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <rect x="3" y="6" width="18" height="13" rx="2" />
                                        <circle cx="12" cy="12.5" r="2.5" />
                                    </svg>
                            @endswitch
                        </div>
                        <span class="absolute left-4 top-4 rounded-full bg-primary-500 px-3 py-1 text-xs font-semibold text-white">
                            {{ $post['category'] }}
                        </span>
                    </a>

                    <div class="p-6">
                        <h3 class="text-lg font-bold text-slate-900">
                            <a href="{{ route('blog.show', $post['slug']) }}" class="transition hover:text-primary-600">{{ $post['title'] }}</a>
                        </h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $post['excerpt'] }}</p>

                        <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">
                            <div class="flex items-center gap-2.5">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary-100 text-xs font-bold text-primary-700">
                                    {{ $post['author_initials'] }}
                                </span>
                                <div>
                                    <p class="text-xs font-semibold text-slate-800">{{ $post['author'] }}</p>
                                    <p class="text-xs text-slate-500">{{ \Illuminate\Support\Carbon::parse($post['date'])->format('M j, Y') }}</p>
                                </div>
                            </div>
                            <a href="{{ route('blog.show', $post['slug']) }}" class="inline-flex items-center gap-1 text-sm font-semibold text-primary-600 transition hover:gap-2">
                                Read
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M5 12h14M13 6l6 6-6 6" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
