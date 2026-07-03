@extends('layouts.app')

@section('title', 'Local SEO Services for USA Businesses | Google Maps SEO & Website Development | MapZoon')
@section('description', 'Grow your local business with MapZoon. We help businesses across the USA improve Google Maps rankings, optimize Google Business Profiles, build SEO-friendly websites, and generate more local leads.')

@section('content')
    @include('sections.navbar')
    @include('partials.landing.hero')
    {{-- @include('partials.landing.stats') --}}
    @include('partials.landing.services')
    @include('partials.landing.maps-process')
    @include('partials.landing.website')
    @include('partials.landing.pos')
    @include('partials.landing.about')
    @include('partials.landing.team')
    @include('partials.landing.testimonials')
    @include('partials.landing.why-us')
    @include('partials.landing.blog-section')
    @include('partials.landing.faq')
    @include('partials.landing.contact')
    @include('partials.landing.footer')
    @include('partials.landing.quote-modal')
@endsection
