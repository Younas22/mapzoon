@extends('layouts.app')

@section('title', 'Our Local SEO Process | How MapZoon Helps Businesses Grow')
@section('description', 'Learn how MapZoon\'s proven Local SEO process helps businesses improve Google Maps rankings, optimize Google Business Profiles, and generate more local customers.')

@push('schema')
    <script type="application/ld+json">
    {!! json_encode([
        '@@context' => 'https://schema.org',
        '@type' => 'WebPage',
        'name' => 'Our Local SEO Process',
        'url' => url('/process'),
        'description' => 'Discover the step-by-step Local SEO process MapZoon uses to help businesses improve Google Maps rankings and online visibility.',
        'about' => [
            '@type' => 'Service',
            'name' => 'Local SEO Services',
            'provider' => [
                '@type' => 'Organization',
                'name' => 'MapZoon',
                'url' => url('/'),
            ],
            'areaServed' => [
                '@type' => 'Country',
                'name' => 'United States',
            ],
        ],
    ], JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@section('content')
    @include('sections.navbar')
    <div class="pt-[7.25rem]">
        @include('partials.landing.maps-process')
    </div>
    @include('partials.landing.footer')
    @include('partials.landing.quote-modal')
@endsection
