@extends('layouts.app')

@section('title', 'About MapZoon')
@section('description', 'Learn about MapZoon, a Local SEO agency helping local businesses improve Google Maps rankings, optimize Google Business Profiles, build high-performing websites, and grow through proven digital marketing strategies.')

@push('schema')
    <?php $settings = \App\Models\SiteSetting::current(); ?>
    <script type="application/ld+json">
    {!! json_encode([
        '@@context' => 'https://schema.org',
        '@type' => 'AboutPage',
        'name' => 'About MapZoon',
        'description' => 'Learn about MapZoon, a Local SEO agency helping local businesses improve Google Maps rankings, optimize Google Business Profiles, build high-performing websites, and grow through proven digital marketing strategies.',
        'mainEntity' => [
            '@type' => 'Organization',
            'name' => $settings->company_name ?? 'MapZoon',
            'url' => url('/'),
            'logo' => $settings->logoLightUrl(),
            'founder' => [
                '@type' => 'Person',
                'name' => 'Younas Dev',
            ],
            'description' => 'MapZoon helps local businesses grow through Local SEO, Google Business Profile optimization, website development, and smart business solutions.',
            'knowsAbout' => [
                'Local SEO',
                'Google Maps SEO',
                'Google Business Profile Optimization',
                'Website Development',
                'Technical SEO',
                'Local Search Marketing',
                'Reputation Management',
                'POS Solutions',
            ],
        ],
    ], JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@section('content')
    @include('sections.navbar')
    <div class="pt-[7.25rem]">
        @include('partials.landing.about')
    </div>
    @include('partials.landing.footer')
    @include('partials.landing.quote-modal')
@endsection
