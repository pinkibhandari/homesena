@extends('frontend.master')

@section('content')

<div class="container py-5">
    <h2 class="mb-3">{{ $page->title }}</h2>

    <div>
        {!! $page->content !!}
    </div>
</div>

@endsection