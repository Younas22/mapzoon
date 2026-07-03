@extends('layouts.app')

@section('title', 'Frequently Asked Questions | Local SEO FAQ | MapZoon')
@section('description', 'Find answers to common questions about Local SEO, Google Business Profile optimization, Google Maps rankings, website development, and POS solutions for local businesses.')

@push('schema')
    <script type="application/ld+json">
    {!! json_encode([
        '@@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => [
            [
                '@type' => 'Question',
                'name' => 'How long does it take to rank on Google Maps?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => 'Most businesses begin seeing improvements within 4 to 8 weeks, while stronger Google Maps rankings often develop over several months depending on competition, location, and the current state of the Google Business Profile.',
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "What's included in the Free Audit?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => 'Our free audit includes a Google Business Profile review, Local SEO analysis, website evaluation, competitor insights, and personalized recommendations to improve your online visibility.',
                ],
            ],
            [
                '@type' => 'Question',
                'name' => 'Do I still need a website if I already have a Google Business Profile?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => 'Yes. A professional website builds trust, provides more information to potential customers, supports Local SEO, and helps convert visitors into leads.',
                ],
            ],
            [
                '@type' => 'Question',
                'name' => 'Can I get the POS & Billing System without signing up for SEO services?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => 'Yes. Our POS & Billing System is available as a standalone solution and can be used independently of our Local SEO services.',
                ],
            ],
            [
                '@type' => 'Question',
                'name' => 'Can the POS system send invoices to customers on WhatsApp?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => 'Yes. Our POS solution supports sharing invoices and receipts through WhatsApp, making customer communication faster and more convenient.',
                ],
            ],
            [
                '@type' => 'Question',
                'name' => 'Will I lose my Google Maps rankings if I stop the service?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Rankings can change over time due to competition and Google's algorithm updates. Ongoing optimization helps maintain and improve your visibility in local search results.",
                ],
            ],
        ],
    ], JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@section('content')
    @include('sections.navbar')
    <div class="pt-[7.25rem]">
        @include('partials.landing.faq')
    </div>
    @include('partials.landing.footer')
    @include('partials.landing.quote-modal')
@endsection
