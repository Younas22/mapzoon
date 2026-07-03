<?php
    $settings = \App\Models\SiteSetting::current();
    $pageTitle = trim($__env->yieldContent('title', $settings->meta_title ?? 'MAPZOON — Rank Higher on Google Maps & Grow Your Local Business'));
    $pageDescription = trim($__env->yieldContent('description', $settings->meta_description ?? 'MAPZOON helps local businesses rank higher on Google Maps and grow with Local SEO, Google Business Profile optimization, professional websites, and POS billing solutions.'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <meta name="keywords" content="{{ $keywords ?? $settings->meta_keywords ?? 'Local SEO, Google Maps Ranking, Google Business Profile Optimization, Citation Management, Review Management, Website Development, POS Billing System' }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ $canonical ?? url()->current() }}">

    @if ($settings->google_search_console_tag)
        {!! $settings->google_search_console_tag !!}
    @endif

    <!-- Open Graph -->
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:site_name" content="{{ $settings->company_name ?? 'MAPZOON' }}">
    <meta property="og:title" content="{{ $ogTitle ?? $pageTitle }}">
    <meta property="og:description" content="{{ $ogDescription ?? $pageDescription }}">
    <meta property="og:url" content="{{ $canonical ?? url()->current() }}">
    @if (! empty($ogImage))
        <meta property="og:image" content="{{ $ogImage }}">
    @endif

    <!-- Twitter Card -->
    <meta name="twitter:card" content="{{ $twitterCard ?? 'summary_large_image' }}">
    <meta name="twitter:title" content="{{ $twitterTitle ?? $ogTitle ?? $pageTitle }}">
    <meta name="twitter:description" content="{{ $twitterDescription ?? $ogDescription ?? $pageDescription }}">
    @if (! empty($twitterImage ?? $ogImage ?? null))
        <meta name="twitter:image" content="{{ $twitterImage ?? $ogImage }}">
    @endif

    <link rel="icon" href="{{ $settings->faviconUrl() }}" sizes="any">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />

    <!-- JSON-LD: LocalBusiness -->
    <script type="application/ld+json">
    {!! json_encode([
        '@@context' => 'https://schema.org',
        '@type' => 'ProfessionalService',
        'name' => $settings->company_name ?? 'MapZoon',
        'description' => $settings->meta_description ?? 'MapZoon helps local businesses across the USA grow through Local SEO, Google Business Profile optimization, website development, and business growth solutions.',
        'telephone' => $settings->phone ?? '+92 333 7222222',
        'email' => $settings->email ?? 'contact@mapzoon.com',
        'url' => url('/'),
        'logo' => $settings->logoLightUrl(),
        'areaServed' => [
            '@type' => 'Country',
            'name' => 'United States',
        ],
        'sameAs' => array_values(array_filter([$settings->facebook_url, $settings->twitter_url, $settings->instagram_url, $settings->linkedin_url, $settings->youtube_url])) ?: [
            'https://www.facebook.com/mapzoon/',
            'https://x.com/mapzoonofficial',
            'https://www.instagram.com/mapzoonofficial/',
            'https://www.linkedin.com/company/mapzoon',
            'https://www.youtube.com/@MapZoon',
        ],
    ], JSON_UNESCAPED_SLASHES) !!}
    </script>

    @stack('schema')

    @if ($settings->google_analytics_code)
        {!! $settings->google_analytics_code !!}
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-ink antialiased selection:bg-primary-200 selection:text-primary-900">
    @yield('content')
</body>
</html>
