<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    <!-- LOGO -->
    <div class="app-brand demo">
        <a href="{{ route('admin.dashboard') }}" class="app-brand-link d-flex align-items-center">
            <img src="{{ asset('assets/img/logo.svg') }}" alt="HomeSena Logo" style="height: 40px;">
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li class="menu-item #">

        </li>
        {{-- Dashboard --}}
        <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon ri ri-home-smile-line"></i>
                <div>Dashboard</div>
            </a>
        </li>

        {{-- Users --}}
        <li class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <a href="{{ route('admin.users.index') }}" class="menu-link">
                <i class="menu-icon ri ri-user-line"></i>
                <div>Users</div>
            </a>
        </li>

        {{-- Experts --}}
        <li class="menu-item {{ request()->routeIs('admin.experts.*') ? 'active' : '' }}">
            <a href="{{ route('admin.experts.index') }}" class="menu-link">
                <i class="menu-icon ri ri-user-star-line"></i>
                <div>Experts</div>
            </a>
        </li>

        {{-- Time Slots --}}
        <li class="menu-item {{ request()->routeIs('admin.time_slots.*') ? 'active' : '' }}">
            <a href="{{ route('admin.time_slots.index') }}" class="menu-link">
                <i class="menu-icon ri ri-time-line"></i>
                <div>Time Slots</div>
            </a>
        </li>

        {{-- Service Management --}}
        <li
            class="menu-item 
            {{ request()->routeIs('admin.services.*') || request()->routeIs('admin.service_variants.*') ? 'active open' : '' }}">

            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon ri ri-customer-service-2-line"></i>
                <div>Service Management</div>
            </a>

            <ul class="menu-sub">

                <li class="menu-item {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.services.index') }}" class="menu-link">
                        <div>Services</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->routeIs('admin.service_variants.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.service_variants.index') }}" class="menu-link">
                        <div>Service Durations</div>
                    </a>
                </li>

            </ul>
        </li>
        {{-- Booking Management --}}
        <li
            class="menu-item 
    {{ request()->routeIs('admin.bookings.*') || request()->routeIs('admin.instant_bookings.*') ? 'active open' : '' }}">

            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon ri ri-calendar-check-line"></i>
                <div>Booking Management</div>
            </a>

            <ul class="menu-sub">

                {{-- Normal Bookings --}}
                <li class="menu-item {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.bookings.index') }}" class="menu-link">
                        <div>Bookings</div>
                    </a>
                </li>

                {{-- Instant Bookings --}}
                <li class="menu-item {{ request()->routeIs('admin.instant_bookings.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.instant_bookings.index') }}" class="menu-link">
                        <div>Instant Bookings</div>
                    </a>
                </li>

            </ul>
        </li>
        {{-- Bookings --}}
        {{-- <li class="menu-item {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
            <a href="{{ route('admin.bookings.index') }}" class="menu-link">
                <i class="menu-icon ri ri-calendar-check-line"></i>
                <div>Bookings</div>
            </a>
        </li> --}}

        {{-- Training Centers --}}
        <li class="menu-item {{ request()->routeIs('admin.training_centers.*') ? 'active' : '' }}">
            <a href="{{ route('admin.training_centers.index') }}" class="menu-link">
                <i class="menu-icon ri ri-building-line"></i>
                <div>Training Centers</div>
            </a>
        </li>

        {{-- CMS Pages --}}
        <li class="menu-item {{ request()->routeIs('admin.cms_pages.*') ? 'active' : '' }}">
            <a href="{{ route('admin.cms_pages.index') }}" class="menu-link">
                <i class="menu-icon ri ri-file-text-line"></i>
                <div>CMS Pages</div>
            </a>
        </li>

        {{-- Reviews --}}
        <li class="menu-item {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
            <a href="{{ route('admin.reviews.index') }}" class="menu-link">
                <i class="menu-icon ri ri-star-line"></i>
                <div>Reviews</div>
            </a>
        </li>

        {{-- Home Promotion --}}
        <li class="menu-item {{ request()->routeIs('admin.home_promotion.*') ? 'active' : '' }}">
            <a href="{{ route('admin.home_promotion.index') }}" class="menu-link">
                <i class="menu-icon ri ri-megaphone-line"></i>
                <div>Home Promotion</div>
            </a>
        </li>

        {{-- Support --}}
        <li
            class="menu-item 
            {{ request()->is('admin/user-support*') || request()->is('admin/expert-support*') ? 'active open' : '' }}">

            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon ri ri-customer-service-line"></i>
                <div>Support</div>
            </a>

            <ul class="menu-sub">

                <li class="menu-item {{ request()->is('admin/user-support*') ? 'active' : '' }}">
                    <a href="{{ route('admin.user_supports.index') }}" class="menu-link">
                        <div>User Support</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->is('admin/expert-support*') ? 'active' : '' }}">
                    <a href="#" class="menu-link">
                        <div>Expert Support</div>
                    </a>
                </li>

            </ul>
        </li>

        {{-- Expert Settings --}}
        <li class="menu-item {{ request()->is('admin/expert-settings*') ? 'active' : '' }}">
            <a href="#" class="menu-link">
                <i class="menu-icon ri ri-settings-3-line"></i>
                <div>Expert Settings</div>
            </a>
        </li>
        {{-- Payments --}}
        <!-- <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base ri ri-secure-payment-line"></i>
                    <div data-i18n="Payments">Payments</div>
                </a> -->

        <!-- <ul class="menu-sub"> -->

        <!-- Payment -->
        <!-- <li class="menu-item">
                        <a href="{{ route('admin.payments.index') }}" class="menu-link">
                            <div data-i18n="Payment">Payment</div>
                        </a>
                    </li> -->

        <!-- Payment Method -->
        <!-- <li class="menu-item">
                        <a href="" class="menu-link">
                            <div data-i18n="Payment Method">Payment Method</div>
                        </a>
                    </li> -->

        <!-- </ul> -->
        <!-- </li> -->

    </ul>
</aside>
<style>
    .menu-item.active>.menu-link {
        background-color: #696cff !important;
        color: #fff !important;
        border-radius: 8px;
    }

    .menu-item.active>.menu-link i {
        color: #fff !important;
    }

    .menu-item.open>.menu-link {
        background-color: rgba(105, 108, 255, 0.1);
        color: #696cff;
    }

    .menu-link:hover {
        background-color: rgba(105, 108, 255, 0.08);
        border-radius: 8px;
        transition: 0.3s;
    }
</style>
