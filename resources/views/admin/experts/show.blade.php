@extends('admin.layouts.master')
@section('title', 'Expert Details')

@section('content')
    <div class="card">

        <!-- ALERT -->
        <div class="p-3">
            @include('admin.layouts.partials.alerts')
        </div>

        <!-- HEADER -->
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
            <h5 class="mb-0">Expert Details</h5>

            <a href="{{ route('admin.experts.index') }}" class="btn btn-sm btn-secondary">
                <i class="ri-arrow-left-line me-1"></i> Back
            </a>
        </div>

        <hr class="my-0">

        <!-- EXPERT INFO -->
        <div class="row px-4 py-3 align-items-center">
            <div class="col-md-2 text-center mb-3 mb-md-0">
                <img src="{{ $expert->profile_image ? fileUrl($expert->profile_image) : asset('assets/img/default-profile-image.jpg') }}"
                    width="80" height="80" class="rounded-circle">
            </div>

            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-4 mb-2"><strong>Name:</strong> {{ $expert->name }}</div>
                    <div class="col-md-4 mb-2"><strong>Email:</strong> {{ $expert->email }}</div>
                    <div class="col-md-4 mb-2"><strong>Phone:</strong> {{ $expert->phone ?? 'N/A' }}</div>

                    <div class="col-md-4 mb-2">
                        <strong>Status:</strong>
                        {!! $expert->status
                            ? '<span class="badge bg-label-success rounded-pill">Active</span>'
                            : '<span class="badge bg-label-danger rounded-pill">Inactive</span>' !!}
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-0">

        <!-- 🔥 NAV TABS -->
        <div class="px-4 pb-3">

            <ul class="nav nav-pills mb-3">

                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#rating">
                        <i class="ri-star-line me-1"></i> Rating
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#addresses">
                        <i class="ri-map-pin-line me-1"></i> Expert Addresses
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#details">
                        <i class="ri-file-list-3-line me-1"></i> Additionals Details
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#contacts">
                        <i class="ri-phone-line me-1"></i> Emergency Contacts
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#slots">
                        <i class="ri-time-line me-1"></i> Booking Slots
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#devices">
                        <i class="ri-smartphone-line me-1"></i> Expert Devices
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#logs">
                        <i class="ri-pulse-line me-1"></i> Online Logs
                    </button>
                </li>

            </ul>

            <div class="tab-content">

                <!-- RATING -->
                <div class="tab-pane fade show active" id="rating">
                    <div class="row">
                        <div class="col-md-4"><strong>Average Rating:</strong>
                            {{ optional($expert->ratingStat)->avg_rating ?? 0 }}
                        </div>
                        <div class="col-md-4"><strong>Total Reviews:</strong>
                            {{ optional($expert->ratingStat)->total_reviews ?? 0 }}</div>
                        <div class="col-md-4"><strong>Stars:</strong> @php $avg = optional($expert->ratingStat)->avg_rating ?? 0; @endphp
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $avg)
                                    <span class="text-warning">★</span>
                                @else
                                    <span class="text-muted">☆</span>
                                @endif
                            @endfor
                        </div>

                    </div>
                </div>

                <!-- ADDRESSES -->
                <div class="tab-pane fade" id="addresses">
                    <table class="table table-bordered table-sm">
                        <thead class="bg-label-secondary">
                            <tr>
                                <th>#</th>
                                <th>Flat No</th>
                                <th>Address</th>
                                <th>Landmark</th>
                                <th>Save As</th>
                                <th>Pets</th>
                                <th>Lat</th>
                                <th>Long</th>
                                <th>Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expert->addresses ?? [] as $key => $address)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $address->flat_no ?? '-' }}</td>
                                    <td>{{ $address->address ?? '-' }}</td>
                                    <td>{{ $address->landmark ?? '-' }}</td>
                                    <td>{{ ucfirst($address->save_as) }}</td>
                                    <td>{{ $address->pets ?? '-' }}</td>
                                    <td>{{ $address->address_lat ?? '-' }}</td>
                                    <td>{{ $address->address_long ?? '-' }}</td>

                                    <td>{{ $address->updated_at ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">No Addresses Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- DETAILS -->
                <div class="tab-pane fade" id="details">
                    <table class="table table-bordered table-sm">
                        <thead class="bg-label-secondary">
                            <tr>
                                <th>#</th>
                                <th>Reg Code</th>
                                <th>Approval</th>
                                <th>Training Center</th>
                                <th>Online</th>
                                <th>Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expert->expertDetail ? [$expert->expertDetail] : [] as $key => $detail)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $detail->registration_code ?? '-' }}</td>
                                    <td>{{ $detail->approval_status ?? '-' }}</td>
                                    <td>{{ $detail->trainingCenter->name ?? '-' }}</td>
                                    <td>
                                        @if ($detail->is_online == 1)
                                            <span class="badge bg-success">Yes</span>
                                        @else
                                            <span class="badge bg-secondary">No</span>
                                        @endif
                                    </td>
                                    <td>{{ $detail->updated_at }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No Details Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- CONTACTS -->
                <div class="tab-pane fade" id="contacts">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(optional($expert->expertDetail)->emergencyContacts ?? [] as $key => $contact)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $contact->name ?? '-' }}</td>
                                    <td>{{ $contact->phone ?? '-' }}</td>
                                    <td>{{ $contact->updated_at }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No Emergency Contacts Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- SLOTS -->
                <div class="tab-pane fade" id="slots">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Expert Name</th>
                                <th>Date</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Duration</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>OTP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expert->expertSlots ?? [] as $key => $slot)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $slot->expert->name ?? '-' }}</td>
                                    <td>{{ $slot->date ?? '-' }}</td>
                                    <td>{{ $slot->start_time ?? '-' }}</td>
                                    <td>{{ $slot->end_time ?? '-' }}</td>
                                    <td>{{ $slot->duration ?? '-' }} min</td>

                                    <td>₹{{ $slot->price ?? 0 }}</td>

                                    <!-- Status -->
                                    <td>
                                        @if ($slot->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($slot->status == 'available')
                                            <span class="badge bg-success">Available</span>
                                        @elseif($slot->status == 'not_available')
                                            <span class="badge bg-secondary">Not Available</span>
                                        @else
                                            <span class="badge bg-info">{{ $slot->status }}</span>
                                        @endif
                                    </td>

                                    <!-- Payment -->
                                    <td>
                                        @if ($slot->payment_status == 'paid')
                                            <span class="badge bg-success">Paid</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>

                                    <!-- OTP -->
                                    <td>{{ $slot->otp_code ?? '-' }}</td>


                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No Booking Slots Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- DEVICES -->
                <div class="tab-pane fade" id="devices">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Device</th>
                                <th>Device Type</th>
                                <th>FCM Token</th>
                                <th>Token ID</th>
                                <th>Status</th>
                                <th>Last Used</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expert->devices ?? [] as $key => $device)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <!-- Device Name -->
                                    <td>{{ $device->device_id ?? '-' }}</td>
                                    <!-- Device Type -->
                                    <td>
                                        <span class="badge bg-label-info rounded-pill">
                                            {{ ucfirst($device->device_type) ?? 'N/A' }}
                                        </span>
                                    </td>

                                    <!-- Platform -->
                                    <td>{{ $device->platform ?? '-' }}</td>

                                    <!-- Token -->
                                    <td style="max-width: 200px; word-break: break-all;">
                                        {{ $device->token_id ?? '-' }}
                                    </td>

                                    <!-- Status -->
                                    <td>
                                        @if ($device->is_active ?? 1)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>

                                    <!-- Last Used -->
                                    <td>{{ $device->updated_at ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No Devices Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- LOGS -->
                <div class="tab-pane fade" id="logs">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Expert</th>
                                <th>Online At</th>
                                <th>Offline At</th>
                                <th>Duration</th>
                                <th>Updated AT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expert->onlineLogs ?? [] as $key => $log)
                                <tr>
                                    <td>{{ $key + 1 }}</td>

                                    <!-- Expert Name -->
                                    <td>{{ $expert->name }}</td>

                                    <!-- Online -->
                                    <td>{{ $log->online_at ?? '-' }}</td>

                                    <!-- Offline -->
                                    <td>{{ $log->offline_at ?? '-' }}</td>

                                    <!-- Duration -->
                                    <td>
                                        @if ($log->online_at && $log->offline_at)
                                            {{ \Carbon\Carbon::parse($log->online_at)->diffForHumans($log->offline_at, true) }}
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <!-- Status -->
                                    <td>{{ $log->updated_at ?? '-' }}</td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No Logs Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
@endsection
