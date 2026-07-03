@extends('layouts.app')

@section('title', 'POS & Billing System | Smart Business Management Software | MapZoon')
@section('description', 'Manage sales, invoices, inventory, customers, and reports with the MapZoon POS & Billing System. A smart business management solution designed for growing businesses.')

@push('schema')
    <script type="application/ld+json">
    {!! json_encode([
        '@@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => 'POS & Billing System',
        'url' => url('/pos-system'),
        'description' => 'Cloud-based POS and billing software for managing sales, invoices, inventory, customer records, expenses, and business reports.',
        'provider' => [
            '@type' => 'Organization',
            'name' => 'MapZoon',
            'url' => url('/'),
        ],
        'serviceType' => 'POS & Billing Software',
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
        @include('partials.landing.pos')
    </div>
    @include('partials.landing.footer')
    @include('partials.landing.quote-modal')
@endsection
