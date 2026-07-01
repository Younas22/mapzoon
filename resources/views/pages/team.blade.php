@extends('layouts.app')

@section('title', 'Our Team — MAPZOON')
@section('description', 'Meet the MAPZOON team — experienced Google Maps SEO specialists, web developers, and business growth experts.')

@section('content')
    @include('sections.navbar')
    <div class="pt-20">
        @include('partials.landing.team')
    </div>
    @include('partials.landing.footer')
    @include('partials.landing.quote-modal')
@endsection
