@extends('layouts.app')

@section('title', 'FAQ — MAPZOON')
@section('description', 'Frequently asked questions about MAPZOON\'s Google Maps SEO, website development, and POS billing services.')

@section('content')
    @include('sections.navbar')
    <div class="pt-[7.25rem]">
        @include('partials.landing.faq')
    </div>
    @include('partials.landing.footer')
    @include('partials.landing.quote-modal')
@endsection
