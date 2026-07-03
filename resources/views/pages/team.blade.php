@extends('layouts.app')

@section('title', 'Meet the MapZoon Team | Local SEO Experts')
@section('description', 'Meet the team behind MapZoon. Local SEO experts helping businesses across the USA improve Google Maps rankings, optimize Google Business Profiles, and grow online.')

@push('schema')
    <?php $settings = \App\Models\SiteSetting::current(); ?>
    <script type="application/ld+json">
    {!! json_encode([
        '@@context' => 'https://schema.org',
        '@type' => 'AboutPage',
        'name' => 'Meet the MapZoon Team',
        'url' => url('/team'),
        'description' => 'Meet the experts behind MapZoon, a Local SEO agency helping businesses grow online.',
        'mainEntity' => [
            '@type' => 'Organization',
            'name' => $settings->company_name ?? 'MapZoon',
            'url' => url('/'),
        ],
    ], JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@section('content')
    @include('sections.navbar')
    <div class="pt-[7.25rem]">
        @include('partials.landing.team')
    </div>
    @include('partials.landing.footer')
    @include('partials.landing.quote-modal')
@endsection
