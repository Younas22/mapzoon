<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? 'MAPZOON — Rank Higher on Google Maps & Grow Your Local Business' }}</title>
    <meta name="description" content="{{ $description ?? 'MAPZOON helps local businesses rank higher on Google Maps and grow with Local SEO, Google Business Profile optimization, professional websites, and POS billing solutions.' }}">
    <meta name="keywords" content="{{ $keywords ?? 'Local SEO, Google Maps Ranking, Google Business Profile Optimization, Citation Management, Review Management, Website Development, POS Billing System' }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ $canonical ?? url()->current() }}">

    <!-- Open Graph -->
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:site_name" content="MAPZOON">
    <meta property="og:title" content="{{ $ogTitle ?? $title ?? 'MAPZOON — Rank Higher on Google Maps & Grow Your Local Business' }}">
    <meta property="og:description" content="{{ $ogDescription ?? $description ?? 'Local SEO, Google Maps Ranking, Websites & POS Billing built to get local businesses more calls and customers.' }}">
    <meta property="og:url" content="{{ $canonical ?? url()->current() }}">
    @if (! empty($ogImage))
        <meta property="og:image" content="{{ $ogImage }}">
    @endif

    <!-- Twitter Card -->
    <meta name="twitter:card" content="{{ $twitterCard ?? 'summary_large_image' }}">
    <meta name="twitter:title" content="{{ $twitterTitle ?? $ogTitle ?? $title ?? 'MAPZOON' }}">
    <meta name="twitter:description" content="{{ $twitterDescription ?? $ogDescription ?? $description ?? '' }}">
    @if (! empty($twitterImage ?? $ogImage ?? null))
        <meta name="twitter:image" content="{{ $twitterImage ?? $ogImage }}">
    @endif

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />

    <!-- JSON-LD: LocalBusiness -->
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'ProfessionalService',
        'name' => 'MAPZOON',
        'description' => 'Local SEO agency helping businesses rank higher on Google Maps with Local SEO, Google Business Profile optimization, websites, and POS billing systems.',
        'telephone' => '+92-326-6787997',
        'email' => 'contact@mapzoon.com',
        'url' => url('/'),
        'areaServed' => 'Local Businesses',
        'sameAs' => [],
    ], JSON_UNESCAPED_SLASHES) !!}
    </script>

    @stack('schema')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-ink antialiased selection:bg-primary-200 selection:text-primary-900">
    @yield('content')
</body>
</html>
