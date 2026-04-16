@extends('admin.layouts.master')
@section('title', 'User Details')

@section('content')
    <div class="card">

        <!-- ALERT MESSAGE -->
        <div class="p-3">
            @include('admin.layouts.partials.alerts')
        </div>

        <!-- Header -->
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
            <h5 class="card-title mb-2 mb-md-0">User Details</h5>

            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary">
                <i class="ri-arrow-left-line me-1"></i> Back
            </a>
        </div>

        <hr class="my-0">

        <!-- User Info -->
        <div class="row px-3 px-md-4 py-3 align-items-center">
            <div class="col-12 col-md-2 text-center mb-3 mb-md-0">
                
                <img src="{{ $user->profile_image ? fileUrl($user->profile_image) : asset('assets/img/default-profile-image.jpg') }}"
                    width="80" height="80" class="rounded-circle">
            </div>

            <div class="col-12 col-md-10">
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-4 mb-2"><strong>Name:</strong> {{ $user->name }}</div>
                    <div class="col-12 col-sm-6 col-md-4 mb-2"><strong>Email:</strong> {{ $user->email }}</div>
                    <div class="col-12 col-sm-6 col-md-4 mb-2"><strong>Phone:</strong> {{ $user->phone ?? 'N/A' }}</div>

                    <div class="col-12 col-sm-6 col-md-4 mb-2">
                        <strong>Profile Completed:</strong>
                        @if ($user->profile_completed === 1)
                            <span class="badge bg-label-primary rounded-pill">Yes</span>
                        @else
                            <span class="badge bg-label-secondary rounded-pill">No</span>
                        @endif
                    </div>


                    <div class="col-12 col-sm-6 col-md-4 mb-2">
                        <strong>Status:</strong>

                        @if ($user->status == 1)
                            <span class="badge bg-label-success rounded-pill">Active</span>
                        @else
                            <span class="badge bg-label-danger rounded-pill">Inactive</span>
                        @endif

                    </div>
                </div>
            </div>
        </div>

        <hr class="my-0">

        <!-- ACCORDION -->
        <div class="accordion px-2 px-md-4 pb-3" id="userAccordion">

            <!-- Addresses -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse"
                        data-bs-target="#addresses">
                        User Addresses
                    </button>
                </h2>
                <div id="addresses" class="accordion-collapse collapse" data-bs-parent="#userAccordion">
                    <div class="accordion-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>House/Flat/Floor</th>
                                        <th>Address</th>
                                        <th>Area/Locality</th>
                                        <th>Landmark</th>
                                        <th>Save As</th>
                                        <th>Pets</th>
                                        <th>Lat</th>
                                        <th>Long</th>
                                        <th>Updated</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($addresses as $key => $address)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $address->flat_no }}</td>
                                            <td>{{ $address->address }}</td>
                                            <td>{{ $address->area_name }}</td>
                                            <td>{{ $address->landmark }}</td>
                                            <td>{{ ucfirst($address->save_as) }}</td>
                                            <td>{{ $address->pets ?? '-' }}</td>
                                            <td>{{ $address->address_lat }}</td>
                                            <td>{{ $address->address_long }}</td>
                                            <td>{{ $address->updated_at }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">No Addresses Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bookings -->
            <div class="accordion-item mt-2">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse"
                        data-bs-target="#bookings">
                        User Bookings
                    </button>
                </h2>
                <div id="bookings" class="accordion-collapse collapse" data-bs-parent="#userAccordion">
                    <div class="accordion-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Booking Code</th>
                                        <th>Service</th>
                                        <th>Type</th>
                                        <th>Subtype</th>
                                        <th>Time</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Total Price</th>
                                        <th>Status</th>
                                        <th>Payment Status</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($bookings as $key => $booking)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $booking->booking_code }}</td>

                                            <td>{{ $booking->service->name ?? 'N/A' }}</td>

                                            <td>
                                                <span class="badge bg-label-info rounded-pill">
                                                    {{ ucfirst($booking->type) }}
                                                </span>
                                            </td>

                                            <td>{{ ucfirst($booking->booking_subtype) }}</td>

                                            <td>{{ $booking->time ?? 'N/A' }}</td>

                                            <td>{{ $booking->start_date ?? 'N/A' }}</td>

                                            <td>{{ $booking->end_date ?? 'N/A' }}</td>

                                            <td>₹{{ number_format($booking->total_price, 2) }}</td>

                                            <!-- Status -->
                                            <td>
                                                @if ($booking->status == 'pending')
                                                    <span class="badge bg-label-warning rounded-pill">Pending</span>
                                                @elseif($booking->status == 'accepted')
                                                    <span class="badge bg-label-primary rounded-pill">Accepted</span>
                                                @elseif($booking->status == 'ongoing')
                                                    <span class="badge bg-label-info rounded-pill">Ongoing</span>
                                                @elseif($booking->status == 'completed')
                                                    <span class="badge bg-label-success rounded-pill">Completed</span>
                                                @else
                                                    <span class="badge bg-label-danger rounded-pill">Cancelled</span>
                                                @endif
                                            </td>

                                            <!-- Payment Status -->
                                            <td>
                                                @if ($booking->payment_status == 'paid')
                                                    <span class="badge bg-label-success rounded-pill">Paid</span>
                                                @elseif($booking->payment_status == 'pending')
                                                    <span class="badge bg-label-warning rounded-pill">Pending</span>
                                                @else
                                                    <span class="badge bg-label-danger rounded-pill">Failed</span>
                                                @endif
                                            </td>

                                            <td>{{ $booking->booking_created_at }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center">No Bookings Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slots -->
            <div class="accordion-item mt-2">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse"
                        data-bs-target="#slots">
                        Booking Slots
                    </button>
                </h2>
                <div id="slots" class="accordion-collapse collapse" data-bs-parent="#userAccordion">
                    <div class="accordion-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Booking ID</th>
                                        <th>Expert</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Start</th>
                                        <th>End</th>
                                        <th>Duration</th>
                                        <th>OTP</th>
                                        <th>OTP Verified</th>
                                        <th>Status</th>
                                        <th>Price</th>
                                        <th>Payment</th>
                                        <th>Check In</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($slots as $key => $slot)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>

                                            <td>{{ $slot->booking->booking_code ?? 'N/A' }}</td>
                                            <td>{{ $slot->expert->name ?? 'N/A' }}</td>

                                            <td>{{ $slot->date }}</td>

                                            <td>{{ $slot->time }}</td>

                                            <td>{{ $slot->start_time }}</td>

                                            <td>{{ $slot->end_time }}</td>

                                            <td>{{ $slot->duration }} (min)</td>

                                            <td>{{ $slot->otp_code ?? 'N/A' }}</td>

                                            <!-- OTP Verified -->
                                            <td>
                                                @if ($slot->otp_verified)
                                                    <span class="badge bg-label-success rounded-pill">Yes</span>
                                                @else
                                                    <span class="badge bg-label-danger rounded-pill">No</span>
                                                @endif
                                            </td>

                                            <!-- Status -->
                                            <td>
                                                @if ($slot->status == 'pending')
                                                    <span class="badge bg-label-warning rounded-pill">Pending</span>
                                                @elseif($slot->status == 'available')
                                                    <span class="badge bg-label-info rounded-pill">Available</span>
                                                @elseif($slot->status == 'not_available')
                                                    <span class="badge bg-label-danger rounded-pill">Not Available</span>
                                                @else
                                                    <span class="badge bg-label-success rounded-pill">Accepted</span>
                                                @endif
                                            </td>

                                            <td>₹{{ number_format($slot->price, 2) }}</td>

                                            <!-- Payment -->
                                            <td>
                                                @if ($slot->payment_status == 'paid')
                                                    <span class="badge bg-label-success rounded-pill">Paid</span>
                                                @else
                                                    <span class="badge bg-label-warning rounded-pill">Pending</span>
                                                @endif
                                            </td>

                                            <td>{{ $slot->check_in_time ?? 'N/A' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="14" class="text-center">No Slots Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Devices -->
            <div class="accordion-item mt-2">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse"
                        data-bs-target="#devices">
                        User Devices
                    </button>
                </h2>
                <div id="devices" class="accordion-collapse collapse" data-bs-parent="#userAccordion">
                    <div class="accordion-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User ID</th>
                                        <th>Token ID</th>
                                        <th>Device ID</th>
                                        <th>Device Type</th>
                                        <th>FCM Token</th>
                                        <th>Updated At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($devices as $key => $device)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>

                                            <td>{{ $device->user_id }}</td>

                                            <td>{{ $device->token_id ?? 'N/A' }}</td>

                                            <td>{{ $device->device_id ?? 'N/A' }}</td>

                                            <td>
                                                <span class="badge bg-label-info rounded-pill">
                                                    {{ ucfirst($device->device_type) ?? 'N/A' }}
                                                </span>
                                            </td>

                                            <td style="max-width:200px; overflow:hidden; text-overflow:ellipsis;">
                                                {{ $device->fcm_token ?? 'N/A' }}
                                            </td>



                                            <td>{{ $device->updated_at }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No Devices Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
