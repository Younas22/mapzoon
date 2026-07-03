@extends('layouts.app')

@section('title', 'Client Testimonials | Local SEO Success Stories | MapZoon')
@section('description', 'Read client testimonials and discover how MapZoon helps local businesses grow with Local SEO, Google Business Profile optimization, website development, and digital marketing solutions.')

@push('schema')
    <script type="application/ld+json">
    {!! json_encode([
        '@@context' => 'https://schema.org',
        '@type' => 'CollectionPage',
        'name' => 'Client Testimonials',
        'url' => url('/testimonials'),
        'description' => 'Read testimonials from businesses that have partnered with MapZoon for Local SEO, Google Business Profile optimization, website development, and digital growth.',
        'about' => [
            '@type' => 'ProfessionalService',
            'name' => 'MapZoon',
            'url' => url('/'),
        ],
    ], JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@section('content')
    @include('sections.navbar')
    <div class="pt-[7.25rem]">
        @include('partials.landing.testimonials')
    </div>
    @include('partials.landing.footer')
    @include('partials.landing.quote-modal')
@endsection
