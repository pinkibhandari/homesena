@extends('frontend.layouts.master')

@section('content')

    <!-- <div class="container py-5">
        <h1 class="mb-3">{{ $page->title }}</h1>

        <div>
            {!! $page->content !!}
        </div>
    </div> -->
    <section class="terms-page">
        <div class="container">
            <!-- TITLE -->
            <h2 class="terms-title">{{ $page->title }}</h2>
            <!-- LAST UPDATED BADGE -->
            <div class="updated-wrap">
                <span class="last-updated">⏱ Last Updated on {{ $page->updated_at->format('F d, Y') }}</span>
            </div>
            <!-- INTRO -->
            <p class="terms-text">
                {!! $page->content !!}
            </p>

        </div>
    </section>
@endsection