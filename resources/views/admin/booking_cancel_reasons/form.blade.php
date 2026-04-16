@extends('admin.layouts.master')

@section('title', isset($reason) ? 'Edit Booking Cancel Reason' : 'Create Booking Cancel Reason')

@section('content')

<div class="card">

    <!-- HEADER -->
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            {{ isset($reason) ? 'Edit Cancel Reason' : 'Create Cancel Reason' }}
        </h5>

        <a href="{{ route('admin.booking_cancel_reasons.index') }}" class="btn btn-sm btn-light">
            <i class="ri-arrow-left-line me-1"></i> Back
        </a>
    </div>

    <div class="card-body">

        <form action="{{ isset($reason)
                        ? route('admin.booking_cancel_reasons.update', $reason->id)
                        : route('admin.booking_cancel_reasons.store') }}"
              method="POST">

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
                               placeholder="Enter cancel reason title"
                               value="{{ old('title', $reason->title ?? '') }}">

                        @error('title')
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