@extends('admin.layouts.master')

@section('title', $center->id ? 'Edit Training Center' : 'Create Training Center')

@section('content')

    <div class="card">

        <!-- Header -->
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                {{ $center->id ? 'Edit Center' : 'Create Center' }}
            </h5>

            <a href="{{ route('admin.training_centers.index') }}" class="btn btn-sm btn-light">
                <i class="ri-arrow-left-line me-1"></i> Back
            </a>
        </div>

        <div class="card-body">

            <!-- Form -->
            <form method="POST"
                action="{{ $center->id ? route('admin.training_centers.update', $center->id) : route('admin.training_centers.store') }}">

                @csrf
                @if ($center->id)
                    @method('PUT')
                @endif

                <div class="row">

                    <!-- Center Name -->
                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <label class="form-label">Center Name</label>

                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-building-line"></i>
                            </span>

                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                placeholder="Enter center name" value="{{ old('name', $center->name) }}">

                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- City -->
                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <label class="form-label">City</label>

                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-map-pin-line"></i>
                            </span>

                            <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
                                placeholder="Enter city" value="{{ old('city', $center->city) }}">

                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- Phone -->
                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <label class="form-label">Phone</label>

                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-phone-line"></i>
                            </span>

                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                placeholder="Enter phone number" value="{{ old('phone', $center->phone) }}">

                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- Address -->
                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <label class="form-label">Address</label>

                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-map-line"></i>
                            </span>

                            <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                                placeholder="Enter address" value="{{ old('address', $center->address) }}">

                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <label class="form-label">Status</label>

                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-shield-check-line"></i>
                            </span>

                            <select name="status" class="form-select @error('status') is-invalid @enderror">

                                <option disabled>Select status</option>

                                <option value="1" {{ old('status', $center->status) == 1 ? 'selected' : '' }}>
                                    ACTIVE
                                </option>

                                <option value="0" {{ old('status', $center->status) == 0 ? 'selected' : '' }}>
                                    INACTIVE
                                </option>

                            </select>

                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                </div>

                <!-- Button -->
                <div class="mt-3">
                    <button class="btn btn-primary">
                        <i class="ri-save-line me-1"></i>
                        {{ $center->id ? 'Update' : 'Save' }}
                    </button>
                </div>

            </form>

        </div>
    </div>

@endsection
