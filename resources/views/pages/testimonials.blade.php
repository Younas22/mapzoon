@extends('layouts.app')

@section('title', 'Testimonials — MAPZOON')
@section('description', 'See what our clients say about MAPZOON\'s Google Maps SEO, website development, and POS billing services.')

@section('content')
    @include('sections.navbar')
    <div class="pt-20">
        @include('partials.landing.testimonials')
    </div>
    @include('partials.landing.footer')
    @include('partials.landing.quote-modal')
@endsection
