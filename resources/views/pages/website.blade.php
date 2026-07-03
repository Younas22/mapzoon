@extends('layouts.app')

@section('title', 'Website Development Services | SEO-Friendly Business Websites | MapZoon')
@section('description', 'Professional website development services for local businesses. We build fast, mobile-friendly, SEO-optimized websites designed to generate more leads and grow your business.')

@push('schema')
    <script type="application/ld+json">
    {!! json_encode([
        '@@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => 'Website Development Services',
        'url' => url('/website'),
        'description' => 'Professional website development services for local businesses, including responsive design, SEO optimization, fast performance, and lead generation.',
        'provider' => [
            '@type' => 'Organization',
            'name' => 'MapZoon',
            'url' => url('/'),
        ],
        'serviceType' => 'Website Development',
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
        @include('partials.landing.website')
    </div>
    @include('partials.landing.footer')
    @include('partials.landing.quote-modal')
@endsection
