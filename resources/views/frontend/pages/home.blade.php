@extends('frontend.layouts.master')

@section('title', 'Home')

@section('hero')
    @include('frontend.sections.hero')
@endsection
@section('content')

@include('frontend.sections.services')
@include('frontend.sections.how-it-works')
@include('frontend.sections.why-us')
@include('frontend.sections.faq')

@endsection