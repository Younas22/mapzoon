@php
    $videoReviews = \App\Models\VideoReview::query()->visibleOnHomepage()->orderBy('display_order')->orderBy('client_name')->get();
@endphp

<section id="testimonials" class="relative bg-white py-20 lg:py-28">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="reveal mx-auto max-w-2xl text-center">
            <p class="text-sm font-semibold uppercase tracking-wider text-primary-600">Client Stories</p>
            <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                Hear It From Businesses We've Helped Grow
            </h2>
            <p class="mt-4 text-base text-slate-600 sm:text-lg">
                Real local business owners sharing real results — in their own words.
            </p>
        </div>

        @if ($videoReviews->isNotEmpty())
            <ul class="mt-14 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 lg:gap-8">
                @foreach ($videoReviews as $i => $review)
                    @php $videoId = $review->youtubeVideoId(); @endphp
                    <li class="reveal reveal-delay-{{ ($i % 4) + 1 }} group overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-lg transition duration-300 hover:-translate-y-1.5 hover:border-primary-200 hover:shadow-2xl">
                        <button
                            type="button"
                            @if ($videoId) data-video-trigger data-youtube-id="{{ $videoId }}" @endif
                            class="relative block aspect-video w-full overflow-hidden focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600"
                            aria-label="Play video testimonial from {{ $review->client_name }}{{ $review->company_name ? ', '.$review->company_name : '' }}"
                        >
                            @if ($review->thumbnailUrl())
                                <img src="{{ $review->thumbnailUrl() }}" alt="" class="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-105">
                            @else
                                <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-primary-900 transition duration-500 group-hover:scale-105"></div>
                            @endif
                            <div class="absolute inset-0 bg-slate-900/10 transition duration-300 group-hover:bg-slate-900/30"></div>

                            <span class="absolute left-4 top-4 flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-sm font-bold text-white ring-1 ring-white/30">{{ $review->initials() }}</span>

                            <span class="absolute inset-0 flex items-center justify-center">
                                <span class="glass-dark flex h-16 w-16 items-center justify-center rounded-full shadow-xl transition duration-300 group-hover:scale-110 group-hover:bg-primary-500/90">
                                    <svg class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path d="M8 5v14l11-7z" />
                                    </svg>
                                </span>
                            </span>
                        </button>

                        <div class="p-6">
                            <div class="flex text-primary-500" aria-hidden="true">
                                @for ($s = 0; $s < 5; $s++)
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                    </svg>
                                @endfor
                            </div>
                            @if ($review->tagline)
                                <p class="mt-3 text-sm font-bold text-slate-900">{{ $review->tagline }}</p>
                            @endif
                            @if ($review->review_text)
                                <p class="mt-2 text-sm italic leading-relaxed text-slate-600">
                                    "{{ $review->review_text }}"
                                </p>
                            @endif
                            <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-4">
                                <div>
                                    <p class="text-sm font-bold text-slate-900">{{ $review->client_name }}</p>
                                    @if ($review->company_name)
                                        <p class="text-xs text-slate-500">{{ $review->company_name }}</p>
                                    @endif
                                </div>
                                <svg class="h-5 w-5 text-slate-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M7 7c-1.7 0-3 1.3-3 3v7h6v-7H7c0-1.1.9-2 2-2V7zM17 7c-1.7 0-3 1.3-3 3v7h6v-7h-3c0-1.1.9-2 2-2V7z" />
                                </svg>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="reveal mt-16 flex flex-col items-center gap-5 text-center sm:flex-row sm:justify-center sm:gap-6">
            <p class="text-base font-semibold text-slate-900">Ready to become our next success story?</p>
            <a href="#contact" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-primary-500 px-7 py-3.5 text-base font-semibold text-white shadow-lg shadow-primary-500/30 transition hover:-translate-y-0.5 hover:bg-primary-600 hover:shadow-xl hover:shadow-primary-500/40 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                Get Free Audit
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M5 12h14M13 6l6 6-6 6" />
                </svg>
            </a>
        </div>
    </div>

    {{-- Video popup modal: a single instance shared by every play button above --}}
    <div id="video-modal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-slate-950/80 p-4 sm:p-8" role="dialog" aria-modal="true" aria-label="Video player">
        <div class="relative w-full max-w-3xl">
            <button type="button" id="video-modal-close" class="absolute -top-12 right-0 flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20" aria-label="Close video">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M6 6l12 12M18 6 6 18" />
                </svg>
            </button>
            <div id="video-modal-frame" class="aspect-video w-full overflow-hidden rounded-2xl bg-black shadow-2xl"></div>
        </div>
    </div>
</section>
