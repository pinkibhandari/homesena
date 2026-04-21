@extends('admin.layouts.master')
@section('title', 'User Details')

@section('content')
    <div class="card">

        <!-- ALERT -->
        <div class="p-3">
            @include('admin.layouts.partials.alerts')
        </div>

        <!-- HEADER -->
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
            <h5 class="mb-0">User Details</h5>

            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary">
                <i class="ri-arrow-left-line me-1"></i> Back
            </a>
        </div>

        <hr class="my-0">

        <!-- USER INFO -->
        <div class="row px-4 py-3 align-items-center">
            <div class="col-md-2 text-center mb-3 mb-md-0">
                <img src="{{ $user->profile_image ? fileUrl($user->profile_image) : asset('assets/img/default-profile-image.jpg') }}"
                    width="80" height="80" class="rounded-circle">
            </div>

            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-4 mb-2"><strong>Name:</strong> {{ $user->name }}</div>
                    <div class="col-md-4 mb-2"><strong>Email:</strong> {{ $user->email }}</div>
                    <div class="col-md-4 mb-2"><strong>Phone:</strong> {{ $user->phone ?? 'N/A' }}</div>

                    <div class="col-12 col-sm-6 col-md-4 mb-2">
                        <strong>Profile Completed:</strong>
                        @if ($user->profile_completed === 1)
                            <span class="badge bg-label-primary rounded-pill">Yes</span>
                        @else
                            <span class="badge bg-label-secondary rounded-pill">No</span>
                        @endif
                    </div>

                    <div class="col-md-4 mb-2">
                        <strong>Status:</strong>
                        {!! $user->status
                            ? '<span class="badge bg-label-success rounded-pill">Active</span>'
                            : '<span class="badge bg-label-danger rounded-pill">Inactive</span>' !!}
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-0">

        <!-- 🔥 NAV TABS -->
        <div class="px-4 pb-3">

            <ul class="nav nav-pills mb-3" id="userTabs">

                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#addresses">
                        <i class="ri-map-pin-line me-1"></i>User Addresses
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#bookings">
                        <i class="ri-calendar-check-line me-1"></i>User Bookings
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#slots">
                        <i class="ri-time-line me-1"></i>Booking Slots
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#devices">
                        <i class="ri-smartphone-line me-1"></i>User Devices
                    </button>
                </li>

            </ul>

            <div class="tab-content">

                <!-- ADDRESSES -->
                <div class="tab-pane fade show active" id="addresses">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="bg-label-secondary">
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

                <!-- BOOKINGS -->
                <div class="tab-pane fade" id="bookings">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="bg-label-secondary">
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

                <!-- Booking Slots -->
                <div class="tab-pane fade" id="slots">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="bg-label-secondary">
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

                <!-- DEVICES -->
                <div class="tab-pane fade" id="devices">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="bg-label-secondary">
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
@endsection
