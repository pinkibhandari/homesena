@extends('admin.layouts.master')
@section('title', 'Expert Details')

@section('content')
    <div class="card">

        <!-- ALERT MESSAGE -->
        <div class="p-3">
            @include('admin.layouts.partials.alerts')
        </div>

        <!-- Header -->
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
            <h5 class="card-title mb-2 mb-md-0">Expert Details</h5>

            <a href="{{ route('admin.experts.index') }}" class="btn btn-sm btn-secondary">
                <i class="ri-arrow-left-line me-1"></i> Back
            </a>
        </div>

        <hr class="my-0">

        <!-- Expert Info -->
        <div class="row px-3 px-md-4 py-3 align-items-center">
            <div class="col-12 col-md-2 text-center mb-3 mb-md-0">
                <img src="{{ $expert->profile_image ? asset('storage/' . $expert->profile_image) : asset('assets/img/default-profile-image.jpg') }}"
                    width="80" height="80" class="rounded-circle">
            </div>

            <div class="col-12 col-md-10">
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-4 mb-2"><strong>Name:</strong> {{ $expert->name }}</div>
                    <div class="col-12 col-sm-6 col-md-4 mb-2"><strong>Email:</strong> {{ $expert->email }}</div>
                    <div class="col-12 col-sm-6 col-md-4 mb-2"><strong>Phone:</strong> {{ $expert->phone ?? 'N/A' }}</div>

                    <div class="col-12 col-sm-6 col-md-4 mb-2">
                        <strong>Status:</strong>

                        @if ($expert->status == 1)
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
        <div class="accordion px-2 px-md-4 pb-3" id="expertAccordion">

            <!-- Rating -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse"
                        data-bs-target="#rating">
                        Expert Rating
                    </button>
                </h2>
                <div id="rating" class="accordion-collapse collapse" data-bs-parent="#expertAccordion">
                    <div class="accordion-body">
                        <div class="row text-sm">
                            <div class="col-12 col-md-4 mb-2">
                                <strong>Average Rating:</strong>
                                {{ optional($expert->ratingStat)->avg_rating ?? 0 }}
                            </div>

                            <div class="col-12 col-md-4 mb-2">
                                <strong>Total Reviews:</strong>
                                {{ optional($expert->ratingStat)->total_reviews ?? 0 }}
                            </div>

                            <div class="col-12 col-md-4 mb-2">
                                <strong>Stars:</strong>
                                @php $avg = optional($expert->ratingStat)->avg_rating ?? 0; @endphp
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
                </div>
            </div>
            <!-- Addresses -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse"
                        data-bs-target="#addresses">
                        Expert Addresses
                    </button>
                </h2>

                <div id="addresses" class="accordion-collapse collapse" data-bs-parent="#expertAccordion">
                    <div class="accordion-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
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
                    </div>
                </div>
            </div>
            <!-- Additional Details -->
            <div class="accordion-item mt-2">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse"
                        data-bs-target="#details">
                        Expert Additional Details
                    </button>
                </h2>
                <div id="details" class="accordion-collapse collapse" data-bs-parent="#expertAccordion">
                    <div class="accordion-body">

                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
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

                    </div>
                </div>
            </div>

            <!-- Emergency Contacts -->
            <div class="accordion-item mt-2">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse"
                        data-bs-target="#contacts">
                        Emergency Contacts
                    </button>
                </h2>
                <div id="contacts" class="accordion-collapse collapse" data-bs-parent="#expertAccordion">
                    <div class="accordion-body">

                        <div class="table-responsive">
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

                    </div>
                </div>
            </div>
            <!-- Booking Slots -->
            <div class="accordion-item mt-2">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse"
                        data-bs-target="#bookingSlots">
                        Booking Slots
                    </button>
                </h2>

                <div id="bookingSlots" class="accordion-collapse collapse" data-bs-parent="#expertAccordion">
                    <div class="accordion-body">

                        <div class="table-responsive">
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

                    </div>
                </div>
            </div>
            <!-- Expert Devices -->
            <div class="accordion-item mt-2">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse"
                        data-bs-target="#devices">
                        Expert Devices
                    </button>
                </h2>

                <div id="devices" class="accordion-collapse collapse" data-bs-parent="#expertAccordion">
                    <div class="accordion-body">

                        <div class="table-responsive">
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

                    </div>
                </div>
            </div>
            <!-- Expert Online Logs -->
            <div class="accordion-item mt-2">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse"
                        data-bs-target="#onlineLogs">
                        Expert Online Logs
                    </button>
                </h2>

                <div id="onlineLogs" class="accordion-collapse collapse" data-bs-parent="#expertAccordion">
                    <div class="accordion-body">

                        <div class="table-responsive">
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
        </div>

    </div>
@endsection
