@extends('admin.layouts.master')

@section('title', 'SOS Details')

@section('content')

    <div class="card">

        <!-- ALERT -->
        <div class="p-3">
            @include('admin.layouts.partials.alerts')
        </div>

        <!-- HEADER -->
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
            <h5 class="card-title mb-2 mb-md-0">SOS Details</h5>

            <a href="{{ route('admin.expert_sos.index') }}" class="btn btn-sm btn-secondary">
                <i class="ri-arrow-left-line me-1"></i> Back
            </a>
        </div>

        <hr class="my-0">

        <!-- 👤 EXPERT INFO -->
        <div class="row px-3 px-md-4 py-3 align-items-center">

            <!-- Profile Image -->
            <div class="col-12 col-md-2 text-center mb-3 mb-md-0">
                <img src="{{ $expert_sos->expert && $expert_sos->expert->profile_image
        ? asset($expert_sos->expert->profile_image)
        : asset('default.png') }}" width="80" height="80" class="rounded-circle border shadow-sm"
                    style="object-fit: cover;">
            </div>

            <!-- Info -->
            <div class="col-12 col-md-10">
                <div class="row">

                    <div class="col-12 col-sm-6 col-md-4 mb-2">
                        <strong>Name:</strong> {{ optional($expert_sos->expert)->name ?? 'N/A' }}
                    </div>

                    <div class="col-12 col-sm-6 col-md-4 mb-2">
                        <strong>Phone:</strong> {{ optional($expert_sos->expert)->phone ?? '-' }}
                    </div>

                    <div class="col-12 col-sm-6 col-md-4 mb-2">
                        <strong>Booking Slot ID:</strong> {{ $expert_sos->booking_slot_id ?? '-' }}
                    </div>

                    <div class="col-12 col-sm-6 col-md-4 mb-2">
                        <strong>Status:</strong>

                        @if($expert_sos->status == 'pending')
                            <span class="badge bg-label-danger"> Pending</span>
                        @elseif($expert_sos->status == 'in_progress')
                            <span class="badge bg-label-warning"> In Progress</span>
                        @elseif($expert_sos->status == 'resolved')
                            <span class="badge bg-label-success"> Resolved</span>
                        @else
                            <span class="badge bg-label-secondary">Unknown</span>
                        @endif
                    </div>

                    <div class="col-12 col-sm-6 col-md-4 mb-2">
                        <strong>Created At:</strong>
                        {{ optional($expert_sos->created_at)->format('d M Y, h:i A') ?? '-' }}
                    </div>

                    <div class="col-12 col-sm-6 col-md-4 mb-2">
                        <strong>Resolved At:</strong>
                        {{ optional($expert_sos->resolved_at)->format('d M Y, h:i A') ?? 'Not resolved yet' }}
                    </div>

                </div>
            </div>

        </div>

        <hr class="my-0">

        <!-- 📄 MESSAGE -->
        <div class="px-3 px-md-4 py-3">
            <h6 class="text-muted">Message</h6>

            <div class="p-3 border rounded bg-light">
                {{ $expert_sos->message ?? 'No message available' }}
            </div>
        </div>
        <hr class="my-0">
        <!--  MAP -->
        <div class="px-3 px-md-4 py-3">
            <h6 class="text-muted">Location</h6>
            <iframe width="100%" height="400"
                src="https://maps.google.com/maps?q={{ $expert_sos->latitude }},{{ $expert_sos->longitude }}&z=15&output=embed">
            </iframe>
        </div>

    </div>

@endsection