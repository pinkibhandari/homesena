@extends('admin.layouts.master')

@section('title', 'View Push Notification')

@section('content')

    <div class="card border-0 shadow-sm rounded-4">

        <!-- Header -->

        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">View Notification
            </h5>

            <a href="{{ route('admin.push_notifications.index') }}" class="btn btn-sm btn-light">

                <i class="ri-arrow-left-line me-1"></i>
                Back
            </a>
        </div>

        <!-- Body -->
        <div class="card-body p-4">

            <div class="row g-4">

                <!-- Title -->
                <div class="col-lg-4 col-md-6 col-12">
                    <label class="fw-semibold text-muted mb-2">
                        Title
                    </label>

                    <div class="border rounded-3 p-3 bg-light-subtle">
                        {{ $push_notification->title ?? 'N/A' }}
                    </div>
                </div>

                <!-- Send Type -->
                <div class="col-lg-3 col-md-6 col-12">
                    <label class="fw-semibold text-muted mb-2">
                        Send Type
                    </label>

                    <div class="border rounded-3 p-3 bg-light-subtle">
                        {{ $push_notification->send_type ? ucfirst($push_notification->send_type) : 'N/A' }}
                    </div>
                </div>

                <!-- User Type -->
                <div class="col-lg-2 col-md-3 col-6">
                    <label class="fw-semibold text-muted mb-2">
                        User Type
                    </label>

                    <div class="border rounded-3 p-3 bg-light-subtle">
                        {{ $push_notification->user_type ? ucfirst($push_notification->user_type) : 'N/A' }}
                    </div>
                </div>

                <!-- Schedule Type -->
                <div class="col-lg-2 col-md-3 col-6">
                    <label class="fw-semibold text-muted mb-2">
                        Schedule Type
                    </label>

                    <div class="border rounded-3 p-3 bg-light-subtle">
                        {{ $push_notification->schedule_type ? ucfirst($push_notification->schedule_type) : 'N/A' }}
                    </div>
                </div>

                <!-- Service Location -->
                <div class="col-lg-4 col-md-6 col-12">
                    <label class="fw-semibold text-muted mb-2">
                        Service Location
                    </label>

                    <div class="border rounded-3 p-3 bg-light-subtle">
                        {{ $push_notification->location->address ?? 'N/A' }}
                    </div>
                </div>

                <!-- Scheduled At -->
                <div class="col-lg-3 col-md-6 col-12">
                    <label class="fw-semibold text-muted mb-2">
                        Scheduled At
                    </label>

                    <div class="border rounded-3 p-3 bg-light-subtle">

                        @if ($push_notification->scheduled_at)
                            {{ \Carbon\Carbon::parse($push_notification->scheduled_at)->format('d M Y h:i A') }}
                        @else
                            N/A
                        @endif

                    </div>
                </div>

                <!-- Status -->
                <div class="col-lg-2 col-md-3 col-6">
                    <label class="fw-semibold text-muted d-block mb-2">
                        Status
                    </label>

                    @if ($push_notification->status == 1)
                        <span
                            class="badge rounded-pill bg-success-subtle text-success border border-success px-4 py-2 fs-6">
                            <i class="ri-checkbox-circle-line me-1"></i>
                            Active
                        </span>
                    @else
                        <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger px-4 py-2 fs-6">
                            <i class="ri-close-circle-line me-1"></i>
                            Inactive
                        </span>
                    @endif

                </div>

                <!-- Is Sent -->
                <!-- Notification Status -->
                <div class="col-lg-2 col-md-3 col-6">
                    <label class="fw-semibold text-muted d-block mb-2">
                        Notification Status
                    </label>

                    @if ($push_notification->is_sent == 1)
                        <span
                            class="badge rounded-pill bg-success-subtle text-success border border-success px-4 py-2 fs-6">
                            <i class="ri-check-double-line me-1"></i>
                            Sent
                        </span>
                    @else
                        <span
                            class="badge rounded-pill bg-warning-subtle text-warning border border-warning px-4 py-2 fs-6">
                            <i class="ri-time-line me-1"></i>
                            Pending
                        </span>
                    @endif

                </div>

                <!-- Message -->
                <div class="col-12">
                    <label class="fw-semibold text-muted mb-2">
                        Message
                    </label>

                    <div class="border rounded-3 p-4 bg-light-subtle" style="min-height:150px;">

                        @if ($push_notification->message)
                            {!! $push_notification->message !!}
                        @else
                            <span class="text-muted">N/A</span>
                        @endif

                    </div>
                </div>

            </div>

        </div>

    </div>

@endsection
