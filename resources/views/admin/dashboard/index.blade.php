@extends('admin.layouts.master')

@section('content')
    <div class="row gy-6">
        <!-- Congratulations card -->
        <div class="col-md-12 col-lg-4">
            <div class="card">
                <div class="card-body text-nowrap">
                    <h5 class="card-title mb-0 flex-wrap text-nowrap">Congratulations Norris! 🎉</h5>
                    <p class="mb-2">Best Expert of the month</p>
                    <h4 class="text-primary mb-0">₹42.8k</h4>
                    <a href="javascript:;" class="btn btn-sm btn-primary">View Expert</a>
                </div>
                <img src="../assets/img/illustrations/trophy.png" class="position-absolute bottom-0 end-0 me-5 mb-5"
                    width="83" alt="view sales" />
            </div>
        </div>
        <!--/ Congratulations card -->

        <!-- Transactions -->
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">Dashboard Overview</h5>
                    </div>
                    <p class="small mb-0">
                        <span class="h6 mb-0">System Overview</span> 📊 real-time insights
                    </p>
                </div>

                <div class="card-body pt-lg-4">
                    <div class="row g-4">

                        <!-- Total Users -->
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar">
                                    <div class="avatar-initial bg-primary rounded shadow-xs">
                                        <i class="ri-user-line"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <p class="mb-0">Users</p>
                                    <h5 class="mb-0">{{ $totalUsers }}</h5>
                                </div>
                            </div>
                        </div>

                        <!-- Total Experts -->
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar">
                                    <div class="avatar-initial bg-success rounded shadow-xs">
                                        <i class="ri-user-star-line"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <p class="mb-0">Experts</p>
                                    <h5 class="mb-0">{{ $totalExperts }}</h5>
                                </div>
                            </div>
                        </div>

                        <!-- Total Services -->
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar">
                                    <div class="avatar-initial bg-warning rounded shadow-xs">
                                        <i class="ri-service-line"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <p class="mb-0">Services</p>
                                    <h5 class="mb-0">{{ $totalServices }}</h5>
                                </div>
                            </div>
                        </div>

                        <!-- Total Bookings -->
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar">
                                    <div class="avatar-initial bg-info rounded shadow-xs">
                                        <i class="ri-calendar-check-line"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <p class="mb-0">Bookings</p>
                                    <h5 class="mb-0">{{ $totalBookings }}</h5>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!--/ Transactions -->

        <!-- Weekly Overview Chart -->
        {{-- <div class="col-xl-4 col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h5 class="mb-1">Weekly Overview</h5>
                        <div class="dropdown">
                            <button class="btn text-body-secondary p-0" type="button" id="weeklyOverviewDropdown"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="icon-base ri ri-more-2-line icon-24px"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="weeklyOverviewDropdown">
                                <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                <a class="dropdown-item" href="javascript:void(0);">Update</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-lg-2">
                    <div id="weeklyOverviewChart"></div>
                    <div class="mt-1 mt-md-3">
                        <div class="d-flex align-items-center gap-4">
                            <h4 class="mb-0">45%</h4>
                            <p class="mb-0">Your sales performance is 45% 😎 better compared to last month</p>
                        </div>
                        <div class="d-grid mt-3 mt-md-4">
                            <button class="btn btn-primary" type="button">Details</button>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <!--/ Weekly Overview Chart -->

        <!-- Total Earnings -->
        {{-- <div class="col-xl-4 col-md-6">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Total Earning</h5>
                    <div class="dropdown">
                        <button class="btn text-body-secondary p-0" type="button" id="totalEarnings"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="icon-base ri ri-more-2-line icon-24px"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="totalEarnings">
                            <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                            <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                            <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-lg-8">
                    <div class="mb-5 mb-lg-12">
                        <div class="d-flex align-items-center">
                            <h3 class="mb-0">$24,895</h3>
                            <span class="text-success ms-2">
                                <i class="icon-base ri ri-arrow-up-s-line icon-sm"></i>
                                <span>10%</span>
                            </span>
                        </div>
                        <p class="mb-0">Compared to $84,325 last year</p>
                    </div>
                    <ul class="p-0 m-0">
                        <li class="d-flex mb-6">
                            <div class="avatar flex-shrink-0 bg-lightest rounded me-3">
                                <img src="../assets/img/icons/misc/zipcar.png" alt="zipcar" />
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Zipcar</h6>
                                    <p class="mb-0">Vuejs, React & HTML</p>
                                </div>
                                <div>
                                    <h6 class="mb-2">$24,895.65</h6>
                                    <div class="progress bg-label-primary" style="height: 4px">
                                        <div class="progress-bar bg-primary" style="width: 75%" role="progressbar"
                                            aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex mb-6">
                            <div class="avatar flex-shrink-0 bg-lightest rounded me-3">
                                <img src="../assets/img/icons/misc/bitbank.png" alt="bitbank" />
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Bitbank</h6>
                                    <p class="mb-0">Sketch, Figma & XD</p>
                                </div>
                                <div>
                                    <h6 class="mb-2">$8,6500.20</h6>
                                    <div class="progress bg-label-info" style="height: 4px">
                                        <div class="progress-bar bg-info" style="width: 75%" role="progressbar"
                                            aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex">
                            <div class="avatar flex-shrink-0 bg-lightest rounded me-3">
                                <img src="../assets/img/icons/misc/aviato.png" alt="aviato" />
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Aviato</h6>
                                    <p class="mb-0">HTML & Angular</p>
                                </div>
                                <div>
                                    <h6 class="mb-2">$1,2450.80</h6>
                                    <div class="progress bg-label-secondary" style="height: 4px">
                                        <div class="progress-bar bg-secondary" style="width: 75%" role="progressbar"
                                            aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div> --}}
        <!--/ Total Earnings -->

        <!-- Four Cards -->
        {{-- <div class="col-xl-4 col-md-6">
            <div class="row gy-6">
                <!-- Total Profit line chart -->
                <div class="col-sm-6">
                    <div class="card h-100">
                        <div class="card-header pb-0">
                            <h4 class="mb-0">$86.4k</h4>
                        </div>
                        <div class="card-body">
                            <div id="totalProfitLineChart" class="mb-3"></div>
                            <h6 class="text-center mb-0">Total Profit</h6>
                        </div>
                    </div>
                </div>
                <!--/ Total Profit line chart -->
                <!-- Total Profit Weekly Project -->
                <div class="col-sm-6">
                    <div class="card h-100">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="avatar">
                                <div class="avatar-initial bg-secondary rounded-circle shadow-xs">
                                    <i class="icon-base ri ri-pie-chart-2-line icon-24px"></i>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn text-body-secondary p-0" type="button" id="totalProfitID"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="icon-base ri ri-more-2-line icon-24px"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="totalProfitID">
                                    <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Update</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6 class="mb-1">Total Profit</h6>
                            <div class="d-flex flex-wrap mb-1 align-items-center">
                                <h4 class="mb-0 me-2">$25.6k</h4>
                                <p class="text-success mb-0">+42%</p>
                            </div>
                            <small>Weekly Project</small>
                        </div>
                    </div>
                </div>
                <!--/ Total Profit Weekly Project -->
                <!-- New Yearly Project -->
                <div class="col-sm-6">
                    <div class="card h-100">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="avatar">
                                <div class="avatar-initial bg-primary rounded-circle shadow-xs">
                                    <i class="icon-base ri ri-file-word-2-line icon-24px"></i>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn text-body-secondary p-0" type="button" id="newProjectID"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="icon-base ri ri-more-2-line icon-24px"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="newProjectID">
                                    <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Update</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6 class="mb-1">New Project</h6>
                            <div class="d-flex flex-wrap mb-1 align-items-center">
                                <h4 class="mb-0 me-2">862</h4>
                                <p class="text-danger mb-0">-18%</p>
                            </div>
                            <small>Yearly Project</small>
                        </div>
                    </div>
                </div>
                <!--/ New Yearly Project -->
                <!-- Sessions chart -->
                <div class="col-sm-6">
                    <div class="card h-100">
                        <div class="card-header pb-0">
                            <h4 class="mb-0">2,856</h4>
                        </div>
                        <div class="card-body">
                            <div id="sessionsColumnChart" class="mb-3"></div>
                            <h6 class="text-center mb-0">Sessions</h6>
                        </div>
                    </div>
                </div>
                <!--/ Sessions chart -->
            </div>
        </div> --}}
        <!--/ four cards -->

        <!-- Sales by Countries -->
        {{-- <div class="col-xl-4 col-md-6">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Sales by Countries</h5>
                    <div class="dropdown">
                        <button class="btn text-body-secondary p-0" type="button" id="saleStatus"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="icon-base ri ri-more-2-line icon-24px"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="saleStatus">
                            <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                            <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                            <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center mb-4">
                            <div class="avatar me-4">
                                <div class="avatar-initial bg-label-success rounded-circle">US</div>
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-1 mb-1">
                                    <h6 class="mb-0">$8,656k</h6>
                                    <i class="icon-base ri ri-arrow-up-s-line icon-24px text-success"></i>
                                    <span class="text-success">25.8%</span>
                                </div>
                                <p class="mb-0">United states of america</p>
                            </div>
                        </div>
                        <div class="text-end">
                            <h6 class="mb-1">894k</h6>
                            <small class="text-body-secondary">Sales</small>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center mb-4">
                            <div class="avatar me-4">
                                <span class="avatar-initial bg-label-danger rounded-circle">UK</span>
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-1 mb-1">
                                    <h6 class="mb-0">$2,415k</h6>
                                    <i class="icon-base ri ri-arrow-down-s-line icon-24px text-danger"></i>
                                    <span class="text-danger">6.2%</span>
                                </div>
                                <p class="mb-0">United Kingdom</p>
                            </div>
                        </div>
                        <div class="text-end">
                            <h6 class="mb-1">645k</h6>
                            <small class="text-body-secondary">Sales</small>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center mb-4">
                            <div class="avatar me-4">
                                <span class="avatar-initial bg-label-warning rounded-circle">IN</span>
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-1 mb-1">
                                    <h6 class="mb-0">865k</h6>
                                    <i class="icon-base ri ri-arrow-up-s-line icon-24px text-success"></i>
                                    <span class="text-success"> 12.4%</span>
                                </div>
                                <p class="mb-0">India</p>
                            </div>
                        </div>
                        <div class="text-end">
                            <h6 class="mb-1">148k</h6>
                            <small class="text-body-secondary">Sales</small>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center mb-4">
                            <div class="avatar me-4">
                                <span class="avatar-initial bg-label-secondary rounded-circle">JA</span>
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-1 mb-1">
                                    <h6 class="mb-0">$745k</h6>
                                    <i class="icon-base ri ri-arrow-down-s-line icon-24px text-danger"></i>
                                    <span class="text-danger">11.9%</span>
                                </div>
                                <p class="mb-0">Japan</p>
                            </div>
                        </div>
                        <div class="text-end">
                            <h6 class="mb-1">86k</h6>
                            <small class="text-body-secondary">Sales</small>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="avatar me-4">
                                <span class="avatar-initial bg-label-danger rounded-circle">KO</span>
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-1 mb-1">
                                    <h6 class="mb-0">$45k</h6>
                                    <i class="icon-base ri ri-arrow-up-s-line icon-24px text-success"></i>
                                    <span class="text-success">16.2%</span>
                                </div>
                                <p class="mb-0">Korea</p>
                            </div>
                        </div>
                        <div class="text-end">
                            <h6 class="mb-1">42k</h6>
                            <small class="text-body-secondary">Sales</small>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <!--/ Sales by Countries -->

        <!-- Deposit / Withdraw -->
        {{-- <div class="col-xl-8">
            <div class="card-group">
                <div class="card mb-0">
                    <div class="card-body card-separator">
                        <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
                            <h5 class="m-0 me-2">Deposit</h5>
                            <a class="fw-medium" href="javascript:void(0);">View all</a>
                        </div>
                        <div class="deposit-content pt-2">
                            <ul class="p-0 m-0">
                                <li class="d-flex mb-4 align-items-center pb-2">
                                    <div class="flex-shrink-0 me-4">
                                        <img src="../assets/img/icons/payments/gumroad.png" class="img-fluid"
                                            alt="gumroad" height="30" width="30" />
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-0">Gumroad Account</h6>
                                            <p class="mb-0">Sell UI Kit</p>
                                        </div>
                                        <h6 class="text-success mb-0">+$4,650</h6>
                                    </div>
                                </li>
                                <li class="d-flex mb-4 align-items-center pb-2">
                                    <div class="flex-shrink-0 me-4">
                                        <img src="../assets/img/icons/payments/mastercard-2.png" class="img-fluid"
                                            alt="mastercard" height="30" width="30" />
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-0">Mastercard</h6>
                                            <p class="mb-0">Wallet deposit</p>
                                        </div>
                                        <h6 class="text-success mb-0">+$92,705</h6>
                                    </div>
                                </li>
                                <li class="d-flex mb-4 align-items-center pb-2">
                                    <div class="flex-shrink-0 me-4">
                                        <img src="../assets/img/icons/payments/stripes.png" class="img-fluid"
                                            alt="stripes" height="30" width="30" />
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-0">Stripe Account</h6>
                                            <p class="mb-0">iOS Application</p>
                                        </div>
                                        <h6 class="text-success mb-0">+$957</h6>
                                    </div>
                                </li>
                                <li class="d-flex mb-4 align-items-center pb-2">
                                    <div class="flex-shrink-0 me-4">
                                        <img src="../assets/img/icons/payments/american-bank.png" class="img-fluid"
                                            alt="american" height="30" width="30" />
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-0">American Bank</h6>
                                            <p class="mb-0">Bank Transfer</p>
                                        </div>
                                        <h6 class="text-success mb-0">+$6,837</h6>
                                    </div>
                                </li>
                                <li class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-4">
                                        <img src="../assets/img/icons/payments/citi.png" class="img-fluid" alt="citi"
                                            height="30" width="30" />
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-0">Bank Account</h6>
                                            <p class="mb-0">Wallet deposit</p>
                                        </div>
                                        <h6 class="text-success mb-0">+$446</h6>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card mb-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
                            <h5 class="m-0 me-2">Withdraw</h5>
                            <a class="fw-medium" href="javascript:void(0);">View all</a>
                        </div>
                        <div class="withdraw-content pt-2">
                            <ul class="p-0 m-0">
                                <li class="d-flex mb-4 align-items-center pb-2">
                                    <div class="flex-shrink-0 me-4">
                                        <img src="../assets/img/icons/brands/google.png" class="img-fluid" alt="google"
                                            height="30" width="30" />
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-0">Google Adsense</h6>
                                            <p class="mb-0">Paypal deposit</p>
                                        </div>
                                        <h6 class="text-danger mb-0">-$145</h6>
                                    </div>
                                </li>
                                <li class="d-flex mb-4 align-items-center pb-2">
                                    <div class="flex-shrink-0 me-4">
                                        <img src="../assets/img/icons/brands/github.png" class="img-fluid" alt="github"
                                            height="30" width="30" />
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-0">Github Enterprise</h6>
                                            <p class="mb-0">Security &amp; compliance</p>
                                        </div>
                                        <h6 class="text-danger mb-0">-$1870</h6>
                                    </div>
                                </li>
                                <li class="d-flex mb-4 align-items-center pb-2">
                                    <div class="flex-shrink-0 me-4">
                                        <img src="../assets/img/icons/brands/slack.png" class="img-fluid" alt="slack"
                                            height="30" width="30" />
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-0">Upgrade Slack Plan</h6>
                                            <p class="mb-0">Debit card deposit</p>
                                        </div>
                                        <h6 class="text-danger mb-0">$450</h6>
                                    </div>
                                </li>
                                <li class="d-flex mb-4 align-items-center pb-2">
                                    <div class="flex-shrink-0 me-4">
                                        <img src="../assets/img/icons/payments/digital-ocean.png" class="img-fluid"
                                            alt="digital" height="30" width="30" />
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-0">Digital Ocean</h6>
                                            <p class="mb-0">Cloud Hosting</p>
                                        </div>
                                        <h6 class="text-danger mb-0">-$540</h6>
                                    </div>
                                </li>
                                <li class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-4">
                                        <img src="../assets/img/icons/brands/aws.png" class="img-fluid" alt="aws"
                                            height="30" width="30" />
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-0">AWS Account</h6>
                                            <p class="mb-0">Choosing a Cloud Platform</p>
                                        </div>
                                        <h6 class="text-danger mb-0">-$21</h6>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <!-- Deposit / Withdraw -->

        <!-- Data Tables -->
        <div class="col-12">
            <div class="card overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th class="text-truncate">User</th>
                                <th class="text-truncate">Email</th>
                                <th class="text-truncate">Role</th>
                                <th class="text-truncate">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-4">
                                                @if (!empty($user->profile_image) && file_exists(public_path('storage/' . $user->profile_image)))
                                                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Avatar"
                                                        class="rounded-circle" />
                                                @else
                                                    <img src="{{ asset('assets/img/avatars/1.png') }}" alt="Default Avatar"
                                                        class="rounded-circle" />
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-0 text-truncate">{{ $user->name }}</h6>
                                                {{-- <small class="text-truncate">{{ '@' . Str::slug($user->name, '') }}</small> --}}
                                            </div>
                                        </div>
                                    </td>

                                    <td class="text-truncate">{{ $user->email }}</td>

                                    <td class="text-truncate">
                                        <div class="d-flex align-items-center">
                                            @if ($user->role == 'admin')
                                                <i class="ri ri-vip-crown-line icon-22px text-primary me-2"></i>
                                                <span>Admin</span>
                                            @elseif ($user->role == 'expert')
                                                <i class="ri ri-user-star-line icon-22px text-info me-2"></i>
                                                <span>Expert</span>
                                            @else
                                                <i class="ri ri-user-3-line icon-22px text-success me-2"></i>
                                                <span>User</span>
                                            @endif
                                        </div>
                                    </td>

                                    @php
                                        $statusClass = $user->status == 1 ? 'success' : 'secondary';
                                        $statusText = ucfirst($user->status);
                                    @endphp

                                    <td>
                                        <span class="badge bg-label-{{ $statusClass }} rounded-pill">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--/ Data Tables -->
    </div>
    <!-- / Content -->
@endsection
