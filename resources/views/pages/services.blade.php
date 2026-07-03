@extends('layouts.app')

@section('title', 'Local SEO Services | Website Development & Google Business Profile | MapZoon')
@section('description', 'Explore MapZoon\'s Local SEO services, Google Business Profile optimization, website development, and POS solutions designed to help local businesses attract more customers and grow online.')

@push('schema')
    <script type="application/ld+json">
    {!! json_encode([
        '@@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => 'Local SEO Services',
        'url' => url('/services'),
        'description' => 'MapZoon provides Local SEO, Google Business Profile optimization, website development, and POS solutions for local businesses.',
        'provider' => [
            '@type' => 'Organization',
            'name' => 'MapZoon',
            'url' => url('/'),
        ],
        'hasOfferCatalog' => [
            '@type' => 'OfferCatalog',
            'name' => 'MapZoon Services',
            'itemListElement' => [
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Local SEO']],
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Website Development']],
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Google Business Profile Optimization']],
                ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'POS & Billing System']],
            ],
        ],
        'areaServed' => [
            '@type' => 'Country',
            'name' => 'United States',
        ],
    ], JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@section('content')
    @include('sections.navbar')
    <div class="pt-[7.25rem]">
        @include('partials.landing.services')
    </div>
    @include('partials.landing.footer')
    @include('partials.landing.quote-modal')
@endsection
