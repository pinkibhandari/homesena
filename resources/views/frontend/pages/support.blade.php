@extends('frontend.layouts.master')

@section('title', 'Support')

@section('content')

<style>
    .support-hero {
        padding: 100px 0 60px;
        background: linear-gradient(135deg, #f5f3ff, #ffffff);
        text-align: center;
    }

    .support-hero h1 {
        font-size: 56px;
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
        line-height: 1.2;
        letter-spacing: -0.5px;
    }

    .support-hero p {
        font-size: 18px;
        color: #666;
    }

    /* SECTION */
    .support-section {
        padding: 70px 0;
        background: #f9f9f9;
    }

    .support-wrapper {
        max-width: 1100px;
        margin: auto;
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        overflow: hidden;
        display: flex;
        align-items: center;
    }

    /* IMAGE */
    .support-image {
        flex: 1 1 45%;
        background: linear-gradient(135deg, #f5f3ff, #ffffff);
    }

    .support-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* CONTENT */
    .support-content {
        flex: 1 1 55%;
        padding: 40px;
    }

    .support-box {
        margin-bottom: 20px;
        padding: 18px;
        border-radius: 12px;
        background: #fafafa;
        transition: 0.3s;
    }

    .support-box:hover {
        background: #f1f1ff;
    }

    .support-box h3 {
        margin-bottom: 10px;
        font-size: 18px;
        font-weight: 600;
        color: #333;
    }

    .support-btn {
        display: inline-block;
        background: #6D28D9;
        color: #fff;
        padding: 10px 18px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 14px;
    }

    /* FOOTER */
    .support-footer {
        text-align: center;
        padding: 50px 0 70px;
    }

    .support-email {
        display: inline-block;
        padding: 12px 25px;
        background: #6D28D9;
        color:#fff;
        border-radius: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }

    /* ✅ TABLET */
    @media (max-width: 992px) {
        .support-hero h1 {
            font-size: 42px;
        }

        .support-wrapper {
            flex-direction: column;
        }

        .support-image {
            width: 100%;
            height: 260px;
        }

        .support-content {
            padding: 30px;
        }
    }

    /* ✅ MOBILE */
    @media (max-width: 576px) {
        .support-hero h1 {
            font-size: 32px;
        }

        .support-hero p {
            font-size: 15px;
        }

        .support-image {
            height: 200px;
        }

        .support-content {
            padding: 20px;
        }
    }
</style>

<!-- HERO -->
<section class="support-hero">
    <div class="container">
        <h1>How can we help you?</h1>
        <p>Need help? Our support team is available 24/7.</p>
    </div>
</section>

<!-- SUPPORT SECTION -->
<section class="support-section">
    <div class="container">

        <div class="support-wrapper">

            <!-- IMAGE SIDE -->
            <div class="support-image">
                <img src="{{ asset('landing/img/support.png') }}" alt="Support">
            </div>

            <!-- CONTENT -->
            <div class="support-content">

                <div class="support-box">
                    <h3>Email Us</h3>
                    <a href="mailto:support@homesena.com" class="support-btn">
                        support@homesena.com
                    </a>
                </div>

                <div class="support-box">
                    <h3>Customer Support</h3>
                    <a href="tel:+918595081189" class="support-btn">
                        +91 8595081189
                    </a>
                </div>

            </div>

        </div>

    </div>
</section>

<!-- FOOTER -->
<section class="support-footer">
    <div class="container">
        <p>Feel free to reach us at:</p>
        <div class="support-email">
            ✉️ support@homesena.com
        </div>
    </div>
</section>

@endsection