@extends('layouts.app')

@section('title', 'Website Development — MAPZOON')
@section('description', 'Professional website development services by MAPZOON — fast, modern, and built to convert local customers.')

@section('content')
    @include('sections.navbar')
    <div class="pt-20">
        @include('partials.landing.website')
    </div>
    @include('partials.landing.footer')
    @include('partials.landing.quote-modal')
@endsection
