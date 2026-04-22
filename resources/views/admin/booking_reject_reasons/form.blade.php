@extends('admin.layouts.master')

@section('title', isset($reason) ? 'Edit Booking Reject Reason' : 'Create Booking Reject Reason')

@section('content')

<div class="card">

    <!-- HEADER -->
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            {{ isset($reason) ? 'Edit Booking Reject Reason' : 'Create Booking Reject Reason' }}
        </h5>

        <a href="{{ route('admin.booking_reject_reasons.index') }}" class="btn btn-sm btn-light">
            <i class="ri-arrow-left-line me-1"></i> Back
        </a>
    </div>

    <div class="card-body">

        <form method="POST"
              action="{{ isset($reason)
                        ? route('admin.booking_reject_reasons.update', $reason->id)
                        : route('admin.booking_reject_reasons.store') }}">

            @csrf

            @if(isset($reason))
                @method('PUT')
            @endif

            <div class="row g-3">

                <!-- TITLE -->
                <div class="col-lg-6 col-md-6 col-12">

                    <label class="form-label">Title</label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="ri-file-text-line"></i>
                        </span>

                        <input type="text"
                               name="title"
                               class="form-control @error('title') is-invalid @enderror"
                               placeholder="Enter reject reason title"
                               value="{{ old('title', $reason->title ?? '') }}">

                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

                <!-- STATUS (same style consistency) -->
                <div class="col-lg-6 col-md-6 col-12">

                    <label class="form-label">Status</label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="ri-shield-check-line"></i>
                        </span>

                        <select name="status"
                                class="form-select @error('status') is-invalid @enderror">

                            <option value="1"
                                {{ old('status', $reason->status ?? 1) == 1 ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="0"
                                {{ old('status', $reason->status ?? 1) == 0 ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

            </div>

            <!-- BUTTONS -->
            <div class="mt-4">

                <button type="submit" class="btn btn-primary">
                    <i class="ri-save-line me-1"></i>
                    {{ isset($reason) ? 'Update' : 'Save' }}
                </button>

            </div>

        </form>

    </div>

</div>

@endsection