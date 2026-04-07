@extends('admin.layouts.master')

@section('title', $instant_booking->id ? 'Edit Instant Booking' : 'Create Instant Booking')

@section('content')

<div class="card">

    {{-- Header --}}
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            {{ $instant_booking->id ? 'Edit Instant Booking' : 'Create Instant Booking' }}
        </h5>

        <a href="{{ route('admin.instant_bookings.index') }}" class="btn btn-sm btn-light">
            <i class="ri-arrow-left-line me-1"></i> Back
        </a>
    </div>

    <div class="card-body">

        <form method="POST"
            action="{{ $instant_booking->id 
                ? route('admin.instant_bookings.update', $instant_booking->id) 
                : route('admin.instant_bookings.store') }}">

            @csrf
            @if($instant_booking->id)
                @method('PUT')
            @endif

            <div class="row g-3">

                {{-- Duration --}}
                <div class="col-lg-4 col-md-6 col-12">
                    <label class="form-label">Duration (Minutes)</label>

                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ri-timer-line"></i>
                        </span>

                        <input type="number" name="duration_minutes"
                            class="form-control @error('duration_minutes') is-invalid @enderror"
                            value="{{ old('duration_minutes', $instant_booking->duration_minutes) }}"
                            placeholder="Enter duration">

                        @error('duration_minutes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Price --}}
                <div class="col-lg-4 col-md-6 col-12">
                    <label class="form-label">Price</label>

                    <div class="input-group">
                        <span class="input-group-text">₹</span>

                        <input type="text" name="price"
                            class="form-control @error('price') is-invalid @enderror"
                            value="{{ old('price', $instant_booking->price) }}"
                            placeholder="Enter price">

                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Discount Price --}}
                <div class="col-lg-4 col-md-6 col-12">
                    <label class="form-label">Discount Price</label>

                    <div class="input-group">
                        <span class="input-group-text">₹</span>

                        <input type="text" name="discount_price"
                            class="form-control @error('discount_price') is-invalid @enderror"
                            value="{{ old('discount_price', $instant_booking->discount_price) }}"
                            placeholder="Enter discount price">

                        @error('discount_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

            </div>

            {{-- Submit --}}
            <div class="mt-4">
                <button class="btn btn-primary">
                    <i class="ri-save-line me-1"></i>
                    {{ $instant_booking->id ? 'Update Plan' : 'Save Plan' }}
                </button>
            </div>

        </form>

    </div>

</div>

@endsection