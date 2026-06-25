<section id="contact" class="relative overflow-hidden bg-slate-900 py-20 lg:py-28">
    <div class="absolute inset-0 -z-20 bg-grid opacity-10" aria-hidden="true"></div>
    <div class="absolute -top-20 left-1/2 -z-10 h-[500px] w-[800px] -translate-x-1/2 rounded-full bg-primary-500/20 blur-3xl" aria-hidden="true"></div>
    <div class="absolute bottom-0 right-0 -z-10 h-72 w-72 rounded-full bg-primary-400/20 blur-3xl" aria-hidden="true"></div>

    <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
        <div class="grid grid-cols-1 items-start gap-12 lg:grid-cols-2 lg:gap-16">

            <div class="reveal order-2 rounded-3xl bg-white p-7 shadow-2xl sm:p-9 lg:order-1">
                <h3 class="text-xl font-bold text-slate-900">Request Your Free Consultation</h3>
                <p class="mt-2 text-sm text-slate-600">Fill out the form and we'll get back to you within 24 hours.</p>

                @if (session('success'))
                    <div class="mt-5 rounded-2xl border border-primary-200 bg-primary-50 px-4 py-3 text-sm font-medium text-primary-700">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('contact.store') }}" class="mt-6 space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="John Doe"
                            class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-primary-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500/30">
                        @error('name')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-slate-700">Phone Number</label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required placeholder="0300 1234567"
                                class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-primary-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500/30">
                            @error('phone')
                                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-semibold text-slate-700">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="you@business.com"
                                class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-primary-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500/30">
                            @error('email')
                                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="service" class="block text-sm font-semibold text-slate-700">Service Interested In</label>
                        <select name="service" id="service"
                            class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-primary-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500/30">
                            <option value="">Select a service</option>
                            @foreach (['Google Maps Ranking', 'Local SEO', 'Website Development', 'POS & Billing System', 'Not Sure Yet'] as $service)
                                <option value="{{ $service }}" @selected(old('service') === $service)>{{ $service }}</option>
                            @endforeach
                        </select>
                        @error('service')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-semibold text-slate-700">Message</label>
                        <textarea name="message" id="message" rows="4" placeholder="Tell us a bit about your business..."
                            class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-primary-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500/30">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-primary-500 px-7 py-3.5 text-base font-semibold text-white shadow-lg shadow-primary-500/30 transition hover:-translate-y-0.5 hover:bg-primary-600 hover:shadow-xl hover:shadow-primary-500/40 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                        Get Free Consultation
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14M13 6l6 6-6 6" />
                        </svg>
                    </button>
                </form>
            </div>

            <div class="reveal reveal-delay-2 order-1 lg:order-2">
                <p class="text-sm font-semibold uppercase tracking-wider text-primary-400">Get In Touch</p>
                <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                    Let's Grow Your Business Together
                </h2>
                <p class="mt-4 text-base text-slate-300 sm:text-lg">
                    Have questions or ready to get started? Reach out directly or fill out the form — we typically respond within a few hours.
                </p>

                <div class="mt-8 space-y-4">
                    <a href="tel:+923266787997" class="glass-dark flex items-center gap-4 rounded-2xl p-4 transition hover:bg-white/15">
                        <span class="flex h-12 w-12 flex-none items-center justify-center rounded-xl bg-primary-500 text-white">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M5 4h3l2 5-2.5 1.5a11 11 0 0 0 5 5L14 13l5 2v3a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z" />
                            </svg>
                        </span>
                        <span>
                            <span class="block text-xs font-medium text-slate-400">Call Us</span>
                            <span class="block text-base font-semibold text-white">0326 6787997</span>
                        </span>
                    </a>

                    <a href="mailto:contact@mapzoon.com" class="glass-dark flex items-center gap-4 rounded-2xl p-4 transition hover:bg-white/15">
                        <span class="flex h-12 w-12 flex-none items-center justify-center rounded-xl bg-primary-500 text-white">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="5" width="18" height="14" rx="2" />
                                <path d="M3.5 6.5 12 13l8.5-6.5" />
                            </svg>
                        </span>
                        <span>
                            <span class="block text-xs font-medium text-slate-400">Email Us</span>
                            <span class="block text-base font-semibold text-white">contact@mapzoon.com</span>
                        </span>
                    </a>
                </div>

                <ul class="mt-8 space-y-3">
                    <li class="flex items-center gap-3">
                        <span class="flex h-6 w-6 flex-none items-center justify-center rounded-full bg-primary-500/20 text-primary-400">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <span class="text-sm text-slate-300">Free, no-obligation audit</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="flex h-6 w-6 flex-none items-center justify-center rounded-full bg-primary-500/20 text-primary-400">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <span class="text-sm text-slate-300">Response within 24 hours</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="flex h-6 w-6 flex-none items-center justify-center rounded-full bg-primary-500/20 text-primary-400">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <span class="text-sm text-slate-300">No long-term lock-in contracts</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="flex h-6 w-6 flex-none items-center justify-center rounded-full bg-primary-500/20 text-primary-400">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <span class="text-sm text-slate-300">Dedicated account manager from day one</span>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</section>
