@extends('layouts.app')

@section('title', 'Pricing — MAPZOON')
@section('description', 'Transparent pricing for Google Maps SEO, website development, and POS billing services. Choose a plan that fits your business.')

@section('content')
    @include('sections.navbar')

    <div class="pt-20">
        <section class="relative bg-white py-20 lg:py-28">
            <div class="absolute left-1/2 top-0 -z-10 h-[500px] w-[900px] -translate-x-1/2 rounded-full bg-primary-100/40 blur-3xl" aria-hidden="true"></div>

            <div class="mx-auto max-w-7xl px-6 lg:px-8">

                {{-- Header --}}
                <div class="reveal mx-auto max-w-2xl text-center">
                    <p class="text-sm font-semibold uppercase tracking-wider text-primary-600">Transparent Pricing</p>
                    <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl lg:text-5xl">
                        Plans Built for Local Businesses
                    </h1>
                    <p class="mt-5 text-base text-slate-600 sm:text-lg">
                        No hidden fees. No long-term contracts. Just results. Pick a plan that matches your growth goals.
                    </p>
                </div>

                {{-- Google Maps SEO Plans --}}
                <div class="mt-16">
                    <h2 class="reveal mb-8 text-center text-xl font-bold text-slate-900">Google Maps SEO Packages</h2>
                    <div class="grid grid-cols-1 gap-8 md:grid-cols-3">

                        @php
                            $plans = [
                                [
                                    'name' => 'Starter',
                                    'price' => '15,000',
                                    'currency' => 'PKR',
                                    'period' => '/month',
                                    'highlight' => false,
                                    'badge' => null,
                                    'description' => 'Perfect for new businesses looking to establish their Google Maps presence.',
                                    'features' => [
                                        'Google Business Profile Setup & Optimization',
                                        'Basic Citation Building (10 citations)',
                                        'Review Response Management',
                                        'Monthly Performance Report',
                                        'Email Support',
                                    ],
                                    'cta' => 'Get Started',
                                ],
                                [
                                    'name' => 'Professional',
                                    'price' => '30,000',
                                    'currency' => 'PKR',
                                    'period' => '/month',
                                    'highlight' => true,
                                    'badge' => 'Most Popular',
                                    'description' => 'For established businesses ready to dominate their local area.',
                                    'features' => [
                                        'Everything in Starter',
                                        'Advanced Citation Building (30 citations)',
                                        'Competitor Analysis & Strategy',
                                        'GBP Posts (4/month)',
                                        'Photo Optimization',
                                        'Q&A Management',
                                        'Bi-weekly Reports',
                                        'WhatsApp Support',
                                    ],
                                    'cta' => 'Get Started',
                                ],
                                [
                                    'name' => 'Enterprise',
                                    'price' => '55,000',
                                    'currency' => 'PKR',
                                    'period' => '/month',
                                    'highlight' => false,
                                    'badge' => null,
                                    'description' => 'For multi-location businesses and agencies needing full-scale local SEO.',
                                    'features' => [
                                        'Everything in Professional',
                                        'Up to 3 Locations',
                                        'Unlimited Citations',
                                        'Weekly GBP Posts',
                                        'Priority Support (24/7)',
                                        'Dedicated Account Manager',
                                        'Weekly Reports + Video Call',
                                    ],
                                    'cta' => 'Contact Us',
                                ],
                            ];
                        @endphp

                        @foreach ($plans as $plan)
                            <div class="reveal relative flex flex-col rounded-2xl border {{ $plan['highlight'] ? 'border-primary-500 bg-primary-600 text-white shadow-xl shadow-primary-500/30' : 'border-slate-200 bg-white shadow-sm' }} p-8">
                                @if ($plan['badge'])
                                    <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 whitespace-nowrap rounded-full bg-amber-400 px-4 py-1 text-xs font-bold text-amber-900">
                                        {{ $plan['badge'] }}
                                    </div>
                                @endif

                                <div>
                                    <h3 class="text-lg font-bold {{ $plan['highlight'] ? 'text-white' : 'text-slate-900' }}">{{ $plan['name'] }}</h3>
                                    <p class="mt-1 text-sm {{ $plan['highlight'] ? 'text-primary-200' : 'text-slate-500' }}">{{ $plan['description'] }}</p>
                                    <div class="mt-4 flex items-baseline gap-1">
                                        <span class="text-sm {{ $plan['highlight'] ? 'text-primary-200' : 'text-slate-500' }}">{{ $plan['currency'] }}</span>
                                        <span class="text-4xl font-extrabold {{ $plan['highlight'] ? 'text-white' : 'text-slate-900' }}">{{ $plan['price'] }}</span>
                                        <span class="text-sm {{ $plan['highlight'] ? 'text-primary-200' : 'text-slate-500' }}">{{ $plan['period'] }}</span>
                                    </div>
                                </div>

                                <ul class="mt-6 flex-1 space-y-3">
                                    @foreach ($plan['features'] as $feature)
                                        <li class="flex items-start gap-2.5 text-sm {{ $plan['highlight'] ? 'text-primary-100' : 'text-slate-600' }}">
                                            <svg class="mt-0.5 h-4 w-4 flex-none {{ $plan['highlight'] ? 'text-primary-300' : 'text-primary-500' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
                                            {{ $feature }}
                                        </li>
                                    @endforeach
                                </ul>

                                <a href="{{ url('/#contact') }}"
                                   class="mt-8 block rounded-xl px-5 py-3 text-center text-sm font-semibold transition hover:-translate-y-0.5 {{ $plan['highlight'] ? 'bg-white text-primary-600 hover:bg-primary-50 shadow-md' : 'bg-primary-500 text-white hover:bg-primary-600 shadow-md shadow-primary-500/30' }}">
                                    {{ $plan['cta'] }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Other Services Pricing --}}
                <div class="mt-20">
                    <h2 class="reveal mb-8 text-center text-xl font-bold text-slate-900">Other Services</h2>
                    <div class="reveal grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h3 class="font-bold text-slate-900">Website Development</h3>
                            <p class="mt-1 text-sm text-slate-500">Professional websites built to convert visitors into customers</p>
                            <div class="mt-4 space-y-2">
                                <div class="flex items-center justify-between border-b border-slate-100 pb-2 text-sm">
                                    <span class="text-slate-700">Landing Page</span>
                                    <span class="font-semibold text-slate-900">From PKR 25,000</span>
                                </div>
                                <div class="flex items-center justify-between border-b border-slate-100 pb-2 text-sm">
                                    <span class="text-slate-700">Business Website (5 pages)</span>
                                    <span class="font-semibold text-slate-900">From PKR 45,000</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-slate-700">E-commerce Website</span>
                                    <span class="font-semibold text-slate-900">From PKR 80,000</span>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h3 class="font-bold text-slate-900">POS Billing System</h3>
                            <p class="mt-1 text-sm text-slate-500">Easy-to-use billing software for local businesses</p>
                            <div class="mt-4 space-y-2">
                                <div class="flex items-center justify-between border-b border-slate-100 pb-2 text-sm">
                                    <span class="text-slate-700">Basic POS Setup</span>
                                    <span class="font-semibold text-slate-900">From PKR 20,000</span>
                                </div>
                                <div class="flex items-center justify-between border-b border-slate-100 pb-2 text-sm">
                                    <span class="text-slate-700">Advanced POS + Inventory</span>
                                    <span class="font-semibold text-slate-900">From PKR 40,000</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-slate-700">Annual Maintenance</span>
                                    <span class="font-semibold text-slate-900">PKR 10,000/year</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CTA --}}
                <div class="reveal mt-16 rounded-2xl bg-slate-900 p-8 text-center text-white lg:p-12">
                    <h3 class="text-2xl font-bold">Not sure which plan is right for you?</h3>
                    <p class="mt-2 text-slate-400">Book a free 30-minute consultation and we'll recommend the best package for your business.</p>
                    <a href="{{ url('/#contact') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-primary-500 px-7 py-3.5 text-sm font-semibold text-white shadow-lg shadow-primary-500/30 transition hover:-translate-y-0.5 hover:bg-primary-600">
                        Book Free Consultation
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                </div>

            </div>
        </section>
    </div>

    @include('partials.landing.footer')
    @include('partials.landing.quote-modal')
@endsection
