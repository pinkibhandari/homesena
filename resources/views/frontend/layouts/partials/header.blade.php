<a href="#header" id="back-to-top" class="top">
    <i class="fa fa-chevron-up"></i>
</a>

<section id="header" class="header">
    <div class="top-bar">
        <div class="container">
            <div class="navigation">

                <div class="floating-nav">

                    <!-- Left Links -->
                    <div class="nav-left">
                        <a href="{{ url('/') }}#services" class="nav-link">Services</a>
                        <a href="{{ url('/') }}#how-it-works" class="nav-link">How it works</a>
                    </div>

                    <!-- Logo -->
                    <a href="/">
                        
                        <img src="{{ asset('landing/img/logo.svg') }}" class="logo" style="width:140px; height:auto;">
                    </a>
                    <!-- Right Links -->
                    <div class="nav-right">
                        <a href="{{ url('/') }}#why-us" class="nav-link">Why Us?</a>
                        <a href="{{ url('/') }}#faqs" class="nav-link">FAQs</a>
                    </div>

                    <!-- Mobile Brand Name -->
                    <a href="/" class="mobile-logo-wrap">
                        <img src="{{ asset('landing/img/logo.svg') }}" class="mobile-nav-logo" alt="Homesena Logo">
                    </a>

                    <!-- Mobile Menu Button -->
                    <div class="menu-btn" id="menuBtn">☰</div>

                </div>

            </div>

            <!-- Mobile Menu -->

            <div class="mobile-menu" id="mobileMenu">

                <div class="mobile-header">
                    <div class="mobile-sidebar-brand">
                        <img src="{{ asset('landing/img/logo.svg') }}" class="mobile-sidebar-logo" alt="Homesena Logo">
                    </div>
                    <span id="closeMenu">&times;</span>
                </div>
                <a href="{{ url('/') }}#services">Services</a>
                <a href="{{ url('/') }}#how-it-works">How it works</a>
                <a href="{{ url('/') }}#why-us">Why Us</a>
                <a href="{{ url('/') }}#faqs">FAQs</a>

            </div>
        </div>
    </div>

    {{-- HERO --}}
       @yield('hero')

</section>
