@extends('layouts.app')

@section('title', 'Contact Us — MAPZOON')
@section('description', 'Get in touch with MAPZOON. Book a free consultation and start growing your local business today.')

@section('content')
    @include('sections.navbar')
    <div class="pt-20">
        @include('partials.landing.contact')
    </div>
    @include('partials.landing.footer')
    @include('partials.landing.quote-modal')
@endsection
