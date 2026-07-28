@extends('layouts.app')

@section('title', 'Contact MapZoon | Get Your Free Local SEO Consultation')
@section('description', 'Contact MapZoon for Local SEO, Google Business Profile optimization, website development, and business growth solutions. Request your free consultation today.')

@push('schema')
    <?php $settings = \App\Models\SiteSetting::current(); ?>
    <script type="application/ld+json">
    {!! json_encode([
        '@@context' => 'https://schema.org',
        '@type' => 'ContactPage',
        'name' => 'Contact MapZoon',
        'url' => url('/contact'),
        'description' => 'Contact MapZoon for Local SEO, Google Business Profile optimization, website development, and business growth solutions.',
        'mainEntity' => [
            '@type' => 'ProfessionalService',
            'name' => $settings->company_name ?? 'MapZoon',
            'url' => url('/'),
            'telephone' => $settings->phone ?? '+92 326 6987997',
            'email' => $settings->email ?? 'contact@mapzoon.com',
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'contactType' => 'Customer Support',
                'telephone' => $settings->phone ?? '+92 326 6987997',
                'email' => $settings->email ?? 'contact@mapzoon.com',
                'availableLanguage' => ['English'],
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
        @include('partials.landing.contact')
    </div>
    @include('partials.landing.footer')
    @include('partials.landing.quote-modal')
@endsection
