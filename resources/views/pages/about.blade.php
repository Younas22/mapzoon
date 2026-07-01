@extends('layouts.app')

@section('title', 'About Us — MAPZOON')
@section('description', 'Learn about MAPZOON — Pakistan\'s trusted Google Maps SEO and local business growth agency.')

@section('content')
    @include('sections.navbar')
    <div class="pt-20">
        @include('partials.landing.about')
    </div>
    @include('partials.landing.footer')
    @include('partials.landing.quote-modal')
@endsection
