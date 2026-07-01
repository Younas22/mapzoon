@extends('layouts.app')

@section('title', 'Why Choose MAPZOON')
@section('description', 'Discover why hundreds of local businesses trust MAPZOON for Google Maps SEO, websites, and POS billing solutions.')

@section('content')
    @include('sections.navbar')
    <div class="pt-20">
        @include('partials.landing.why-us')
    </div>
    @include('partials.landing.footer')
    @include('partials.landing.quote-modal')
@endsection
