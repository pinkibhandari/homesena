@extends('frontend.master')

@section('title', 'Home')

@section('content')

@include('frontend.sections.services')
@include('frontend.sections.how-it-works')
@include('frontend.sections.why-us')
@include('frontend.sections.faq')

@endsection