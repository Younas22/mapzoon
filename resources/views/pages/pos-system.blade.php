@extends('layouts.app')

@section('title', 'POS System — MAPZOON')
@section('description', 'MAPZOON POS billing system designed for local businesses — easy to use, fast, and reliable.')

@section('content')
    @include('sections.navbar')
    <div class="pt-20">
        @include('partials.landing.pos')
    </div>
    @include('partials.landing.footer')
    @include('partials.landing.quote-modal')
@endsection
