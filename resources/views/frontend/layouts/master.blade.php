<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Homesena – Get Trained &amp; Verified House Help in 10 Mins</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('landing/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/owl.theme.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/cms-page.css') }}">

</head>
<body>

      {{-- Header (only when defined) --}}
  
    @include('frontend.layouts.partials.header')

    {{-- Page Content --}}
    @yield('content')

    {{-- Footer --}}
    @include('frontend.layouts.partials.footer')

    <!-- JS -->
     <script src="{{ asset('landing/js/jquery-1.11.2.min.js') }}"></script>
    <script src="{{ asset('landing/js/wow.min.js') }}"></script>
    <script src="{{ asset('landing/js/owl-carousel.js') }}"></script>
    <script src="{{ asset('landing/js/nivo-lightbox.min.js') }}"></script>
    <script src="{{ asset('landing/js/smoothscroll.js') }}"></script>
    <!--<script src="js/jquery.ajaxchimp.min.js"></script>-->
    <script src="{{ asset('landing/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('landing/js/classie.js') }}"></script>
    <script src="{{ asset('landing/js/modernizr.custom.js') }}"></script>
    <script src="{{ asset('landing/js/script.js') }}"></script>
    <script>
        new WOW().init();
    </script>

</body>
</html>