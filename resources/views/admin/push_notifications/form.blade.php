@extends('admin.layouts.master')

@section('title', isset($push_notification->id) ? 'Edit Push Notification' : 'Create Push Notification')

@section('content')

    <div class="card">

        <!-- Header -->
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                {{ isset($push_notification->id) ? 'Edit Push Notification' : 'Create Push Notification' }}
            </h5>

            <a href="{{ route('admin.push_notifications.index') }}" class="btn btn-sm btn-light">

                <i class="ri-arrow-left-line me-1"></i>
                Back
            </a>
        </div>

        <!-- Body -->
        <div class="card-body">

            <form id="push_notification" method="POST"
                action="{{ isset($push_notification->id)
                    ? route('admin.push_notifications.update', $push_notification->id)
                    : route('admin.push_notifications.store') }}">

                @csrf

                @if (isset($push_notification->id))
                    @method('PUT')
                @endif

                <div class="row g-3">

                    <!-- Title -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <label class="form-label">
                            Title <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-notification-3-line"></i>
                            </span>

                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title', $push_notification->title ?? '') }}"
                                placeholder="Enter notification title">

                            @error('title')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Send Type -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <label class="form-label">
                            Send Type <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-send-plane-line"></i>
                            </span>

                            <select id="send_type" name="send_type"
                                class="form-select @error('send_type') is-invalid @enderror">

                                <option value="">Select Type</option>

                                <option value="all"
                                    {{ old('send_type', $push_notification->send_type ?? '') == 'all' ? 'selected' : '' }}>
                                    All Users
                                </option>

                                <option value="location"
                                    {{ old('send_type', $push_notification->send_type ?? '') == 'location' ? 'selected' : '' }}>
                                    Location Wise
                                </option>

                            </select>

                            @error('send_type')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Service Location -->
                    <div class="col-lg-4 col-md-6 col-12" id="location_div"
                        style="{{ old('send_type', $push_notification->send_type ?? '') == 'location' ? '' : 'display:none;' }}">

                        <label class="form-label">
                            Service Location <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-map-pin-line"></i>
                            </span>

                            <select name="location_id" class="form-select @error('location_id') is-invalid @enderror">

                                <option value="">Select Location</option>

                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}"
                                        {{ old('location_id', $push_notification->location_id ?? '') == $location->id ? 'selected' : '' }}>
                                        {{ $location->address }}
                                    </option>
                                @endforeach

                            </select>

                            @error('location_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- User Type -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <label class="form-label">
                            User Type
                        </label>

                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-user-line"></i>
                            </span>

                            <select name="user_type" class="form-select @error('user_type') is-invalid @enderror">

                                <option value="">Select User Type</option>

                                <option value="user"
                                    {{ old('user_type', $push_notification->user_type ?? '') == 'user' ? 'selected' : '' }}>
                                    User
                                </option>

                                <option value="expert"
                                    {{ old('user_type', $push_notification->user_type ?? '') == 'expert' ? 'selected' : '' }}>
                                    Expert
                                </option>

                            </select>

                            @error('user_type')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Schedule Type -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <label class="form-label">
                            Schedule Type
                        </label>

                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-alarm-line"></i> </span>

                            <select name="schedule_type" class="form-select @error('schedule_type') is-invalid @enderror">

                                <option value="instant"
                                    {{ old('schedule_type', $push_notification->schedule_type ?? '') == 'instant' ? 'selected' : '' }}>
                                    Instant
                                </option>

                                <option value="scheduled"
                                    {{ old('schedule_type', $push_notification->schedule_type ?? '') == 'scheduled' ? 'selected' : '' }}>
                                    Scheduled
                                </option>

                            </select>

                            @error('schedule_type')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Scheduled At -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <label class="form-label">
                            Scheduled At
                        </label>

                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-time-line"></i>
                            </span>

                            <input type="datetime-local" name="scheduled_at"
                                class="form-control @error('scheduled_at') is-invalid @enderror"
                                value="{{ old('scheduled_at', isset($push_notification->scheduled_at) ? \Carbon\Carbon::parse($push_notification->scheduled_at)->format('Y-m-d\TH:i') : '') }}">

                            @error('scheduled_at')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <label class="form-label">
                            Status
                        </label>

                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-shield-check-line"></i>
                            </span>

                            <select name="status" class="form-select @error('status') is-invalid @enderror">

                                <option value="1"
                                    {{ old('status', $push_notification->status ?? 1) == 1 ? 'selected' : '' }}>
                                    Active
                                </option>

                                <option value="0"
                                    {{ old('status', $push_notification->status ?? 1) == 0 ? 'selected' : '' }}>
                                    Inactive
                                </option>

                            </select>

                            @error('status')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="col-12">
                        <label class="form-label">
                            Message <span class="text-danger">*</span>
                        </label>

                        <!-- Quill Editor -->
                        <div id="editor" style="height: 200px;"></div>

                        <!-- Hidden Input -->
                        <input type="hidden" name="message" id="message"
                            value="{{ old('message', $push_notification->message) }}">

                        @error('message')
                            <div class="text-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>


                </div>

                <!-- Submit -->
                <div class="mt-4">
                    <button class="btn btn-primary">
                        <i class="ri-save-line me-1"></i>

                        {{ isset($push_notification->id) ? 'Update' : 'Save' }}
                    </button>
                </div>

            </form>

        </div>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const sendType = document.getElementById('send_type');
            const locationDiv = document.getElementById('location_div');

            function toggleLocationField() {

                if (sendType.value === 'location') {
                    locationDiv.style.display = 'block';
                } else {
                    locationDiv.style.display = 'none';
                }
            }

            toggleLocationField();

            sendType.addEventListener('change', toggleLocationField);

        });
    </script>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Quill
            var quill = new Quill('#editor', {
                theme: 'snow',
                placeholder: 'Write message here...',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{
                            'list': 'ordered'
                        }, {
                            'list': 'bullet'
                        }],
                        ['link', 'image']
                    ]
                }
            });

            // Load existing description for edit
            var descriptionInput = document.getElementById('message');
            if (descriptionInput.value) {
                quill.root.innerHTML = descriptionInput.value;
            }

            // On form submit, copy content to hidden input
            var form = document.getElementById('push_notification');
            form.addEventListener('submit', function() {
                descriptionInput.value = quill.root.innerHTML.trim();
            });
        });
    </script>
@endpush
