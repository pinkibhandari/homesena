@extends('admin.layouts.master')
@section('title', $location->id ? 'Edit Service Location' : 'Add Service Location')

@section('content')

<div class="card">

    <!-- Header -->
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            {{ $location->id ? 'Edit Service Location' : 'Create Service Location' }}
        </h5>

        <a href="{{ route('admin.service_locations.index') }}" class="btn btn-sm btn-light">
            <i class="ri-arrow-left-line me-1"></i> Back
        </a>
    </div>

    <div class="card-body">

        <form method="POST"
            action="{{ $location->id ? route('admin.service_locations.update', $location->id) : route('admin.service_locations.store') }}">

            @csrf
            @if ($location->id)
                @method('PUT')
            @endif

            <div class="row g-3">

                <!-- Address -->
                <div class="col-lg-6 col-md-6 col-12">
                    <label class="form-label">Address</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ri-map-pin-line"></i>
                        </span>
                        <textarea name="address"
                            class="form-control @error('address') is-invalid @enderror"
                            placeholder="Enter Address"
                            rows="2">{{ old('address', $location->address) }}</textarea>

                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Latitude -->
                <div class="col-lg-3 col-md-6 col-12">
                    <label class="form-label">Latitude</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ri-global-line"></i>
                        </span>
                        <input type="text" name="latitude"
                            class="form-control @error('latitude') is-invalid @enderror"
                            placeholder="Latitude"
                            value="{{ old('latitude', $location->latitude) }}">

                        @error('latitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Longitude -->
                <div class="col-lg-3 col-md-6 col-12">
                    <label class="form-label">Longitude</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ri-global-line"></i>
                        </span>
                        <input type="text" name="longitude"
                            class="form-control @error('longitude') is-invalid @enderror"
                            placeholder="Longitude"
                            value="{{ old('longitude', $location->longitude) }}">

                        @error('longitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <div class="col-lg-4 col-md-6 col-12">
                    <label class="form-label">Status</label>

                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ri-shield-check-line"></i>
                        </span>

                        <select name="status"
                            class="form-select @error('status') is-invalid @enderror">

                            <option value="">Select status</option>

                            <option value="1"
                                {{ old('status', $location->status) == 1 ? 'selected' : '' }}>
                                ACTIVE
                            </option>

                            <option value="0"
                                {{ old('status', $location->status) == 0 ? 'selected' : '' }}>
                                INACTIVE
                            </option>

                        </select>

                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

            </div>

            <!-- Submit -->
            <div class="mt-4">
                <button class="btn btn-primary">
                    <i class="ri-save-line me-1"></i>
                    {{ $location->id ? 'Update' : 'Save' }}
                </button>
            </div>

        </form>

    </div>

</div>

@endsection