@extends('layouts.app')

@section('title', 'Our Process — MAPZOON')
@section('description', 'Learn how MAPZOON\'s proven Google Maps optimization process helps local businesses rank higher and get more customers.')

@section('content')
    @include('sections.navbar')
    <div class="pt-20">
        @include('partials.landing.maps-process')
    </div>
    @include('partials.landing.footer')
    @include('partials.landing.quote-modal')
@endsection
