@extends('layouts.app')

@section('title', 'Our Services — MAPZOON')
@section('description', 'Explore MAPZOON\'s full range of local SEO, Google Maps ranking, website development, and POS billing services.')

@section('content')
    @include('sections.navbar')
    <div class="pt-20">
        @include('partials.landing.services')
    </div>
    @include('partials.landing.footer')
    @include('partials.landing.quote-modal')
@endsection
