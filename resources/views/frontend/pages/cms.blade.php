@extends('frontend.layouts.master')

@section('content')

<div class="container py-5">
    <h1 class="mb-3">{{ $page->title }}</h1>

    <div>
        {!! $page->content !!}
    </div>
</div>

@endsection