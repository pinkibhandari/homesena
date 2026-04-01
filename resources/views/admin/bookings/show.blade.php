@extends('admin.layouts.master')
@section('title', 'Show Booking')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <!-- Booking Code -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Booking Code</label>
                    <div class="input-group">
                        <!-- <span class="input-group-text"><i class="ri-file-copy-line"></i></span> -->
                        <input type="text" class="form-control" value="{{ $booking->device_id}}">
                    </div>
                </div>

                <!-- User -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">User</label>
                    <div class="input-group">
                        <!-- <span class="input-group-text"><i class="ri-file-copy-line"></i></span> -->
                        <input type="text" class="form-control" value="{{ $booking->device_id}}">
                    </div>
                </div>

                <!-- Service -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Service</label>
                    <div class="input-group">
                        <!-- <span class="input-group-text"><i class="ri-file-copy-line"></i></span> -->
                        <input type="text" class="form-control" value="{{ $booking->device_id}}">
                    </div>
                </div>

                <!-- Address -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Address</label>
                    <div class="input-group">
                        <!-- <span class="input-group-text"><i class="ri-file-copy-line"></i></span> -->
                        <input type="text" class="form-control" value="{{ $booking->device_id}}">
                    </div>

                </div>
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Address Latitude</label>
                    <div class="input-group">
                        <!-- <span class="input-group-text"><i class="ri-file-copy-line"></i></span> -->
                        <input type="text" class="form-control" value="{{ $booking->device_id}}">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Address Longitude</label>
                    <div class="input-group">
                        <!-- <span class="input-group-text"><i class="ri-file-copy-line"></i></span> -->
                        <input type="text" class="form-control" value="{{ $booking->device_id}}">
                    </div>
                </div>


                <!-- Booking Type -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Booking Type</label>
                    <div class="input-group">
                        <!-- <span class="input-group-text"><i class="ri-file-copy-line"></i></span> -->
                        <input type="text" class="form-control" value="{{ $booking->device_id}}">
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Booking Sub Type</label>
                    <div class="input-group">
                        <!-- <span class="input-group-text"><i class="ri-file-copy-line"></i></span> -->
                        <input type="text" class="form-control" value="{{ $booking->device_id}}">
                    </div>
                </div>
                <!-- Booking Date -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Booking Start Date</label>
                    <div class="input-group">
                        <!-- <span class="input-group-text"><i class="ri-file-copy-line"></i></span> -->
                        <input type="text" class="form-control" value="{{ $booking->device_id}}" >
                    </div>
                </div>

                <!-- Booking Date -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Booking End Date</label>
                    <div class="input-group">
                        <!-- <span class="input-group-text"><i class="ri-file-copy-line"></i></span> -->
                        <input type="text" class="form-control" value="{{ $booking->device_id}}">
                    </div>
                </div>
                <!-- Booking Date -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Booking Time</label>
                    <div class="input-group">
                        <!-- <span class="input-group-text"><i class="ri-file-copy-line"></i></span> -->
                        <input type="text" class="form-control" value="{{ $booking->device_id}}">
                    </div>
                </div>
                <!-- Total Amount -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Total Amount</label>
                    <div class="input-group">
                        <!-- <span class="input-group-text"><i class="ri-file-copy-line"></i></span> -->
                        <input type="text" class="form-control" value="{{ $booking->device_id}}">
                    </div>
                </div>
                <!-- booking create at -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Booking Created Date and time </label>
                    <div class="input-group">
                        <!-- <span class="input-group-text"><i class="ri-file-copy-line"></i></span> -->
                        <input type="text" class="form-control" value="{{ $booking->device_id}}">
                    </div>
                </div>
                <!-- Status -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Payment Status</label>
                    <div class="input-group">
                        <!-- <span class="input-group-text"><i class="ri-file-copy-line"></i></span> -->
                        <input type="text" class="form-control" value="{{ $booking->device_id}}">
                    </div>
                </div>
                <!-- Status -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Status</label>
                    <div class="input-group">
                        <!-- <span class="input-group-text"><i class="ri-file-copy-line"></i></span> -->
                        <input type="text" class="form-control" value="{{ $booking->device_id}}">
                    </div>
                </div>
                <!-- ---- -->
            </div>
            <!--  booking slot  -->
            <!-- php function -->
            @php
                function statusColor($status)
                {
                    return match ($status) {
                        'pending' => 'warning',
                        'accepted' => 'info',
                        'on_the_way' => 'primary',
                        'ongoing' => 'dark',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        'partial' => 'secondary',
                        default => 'light'
                    };
                }
            @endphp
            <!-- Table -->
            <div class="card-header">
                <h5 class="card-title mb-0">Booking Slot List</h5>
            </div>
            <div class="table-responsive px-4 pb-3">
                <table class="table table-hover align-middle table-bordered">
                    <thead class="bg-label-secondary">
                        <tr>
                            <th width="60">ID</th>
                            <th>Expert Name</th>
                            <th>Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Duration</th>
                            <th>Otp</th>
                            <th>Amount</th>
                            <!-- <th>Payment Status</th> -->
                            <th>Check In Time</th> 
                            <th>Status</th>
                            <!-- <th width="120">Actions</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($slots as $slot)
                            <tr>
                                <td>{{ $slots->firstItem() + $loop->index }}</td>
                                <td>
                                    <span class="fw-semibold">{{ $slot->expert?->name}}</span>
                                </td>
                                <td>{{ $slot->date }}</td>
                                <td>{{ $slot->start_time }}</td>
                                <td>{{ $slot->end_time }}</td>
                                <td>{{ $slot->duration }}</td>
                                <td>{{ $slot->otp}}</td>
                                <td>{{ $slot->price }}</td>
                                <td>{{ $slot->check_in_time }}</td>
                                <td>
                                    <span class="badge rounded-pill bg-label-{{ statusColor($slot->status) }}">
                                        {{ ucfirst(str_replace('_', ' ', $slot->status)) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No booking slot found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination (Dynamic) -->
            <div class="row px-4 pb-3 align-items-center">
                {{ $slots->links('pagination::bootstrap-5') }}
            </div>
            <!-- end booking slot -->
        </div>
    <!-- </div> -->

@endsection