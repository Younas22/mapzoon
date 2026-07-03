@extends('layouts.app')

@section('title', 'Why Choose MapZoon | Local SEO & Google Maps Experts')
@section('description', 'Discover why businesses choose MapZoon for Local SEO, Google Business Profile optimization, website development, and long-term business growth solutions.')

@push('schema')
    <script type="application/ld+json">
    {!! json_encode([
        '@@context' => 'https://schema.org',
        '@type' => 'WebPage',
        'name' => 'Why Choose MapZoon',
        'url' => url('/why-choose-us'),
        'description' => 'Learn why businesses choose MapZoon for Local SEO, Google Business Profile optimization, website development, and business growth.',
        'about' => [
            '@type' => 'ProfessionalService',
            'name' => 'MapZoon',
            'url' => url('/'),
            'serviceType' => [
                'Local SEO',
                'Google Business Profile Optimization',
                'Google Maps SEO',
                'Website Development',
                'POS Solutions',
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
        @include('partials.landing.why-us')
    </div>
    @include('partials.landing.footer')
    @include('partials.landing.quote-modal')
@endsection
