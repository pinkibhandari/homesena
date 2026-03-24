@extends('admin.layouts.master')

@section('title', $variant->id ? 'Edit Service Variant' : 'Create Service Variant')

@section('content')

    <div class="card">

        <!-- Header -->
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                {{ $variant->id ? 'Edit Service Variant' : 'Create Service Variant' }}
            </h5>

            <a href="{{ route('admin.service_variants.index') }}" class="btn btn-sm btn-light">
                <i class="ri-arrow-left-line me-1"></i> Back
            </a>
        </div>

        <div class="card-body">

            <form method="POST"
                action="{{ $variant->id ? route('admin.service_variants.update', $variant->id) : route('admin.service_variants.store') }}">

                @csrf
                @if ($variant->id)
                    @method('PUT')
                @endif

                <div class="row g-3">

                    <!-- Service -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <label class="form-label">Service</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-service-line"></i></span>

                            <select name="service_id" class="form-select @error('service_id') is-invalid @enderror">

                                <option value="">Select Service</option>

                                @foreach ($services as $id => $name)
                                    <option value="{{ $id }}"
                                        {{ old('service_id', $variant->service_id) == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach

                            </select>

                            @error('service_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Duration -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <label class="form-label">Duration (Minutes)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-time-line"></i></span>

                            <input type="number" name="duration_minutes"
                                class="form-control @error('duration_minutes') is-invalid @enderror"
                                placeholder="Enter duration"
                                value="{{ old('duration_minutes', $variant->duration_minutes) }}">

                            @error('duration_minutes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Base Price -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <label class="form-label">Base Price</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-coin-line"></i></span>

                            <input type="number" step="0.01" name="base_price"
                                class="form-control @error('base_price') is-invalid @enderror" placeholder="Enter price"
                                value="{{ old('base_price', $variant->base_price) }}">

                            @error('base_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- Discount Price -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <label class="form-label">Discount Price</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-price-tag-3-line"></i></span>

                            <input type="number" step="0.01" name="discount_price"
                                class="form-control @error('discount_price') is-invalid @enderror"
                                placeholder="Enter discount price"
                                value="{{ old('discount_price', $variant->discount_price) }}">

                            @error('discount_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- Tax -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <label class="form-label">Tax (%)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-percent-line"></i></span>

                            <input type="number" step="0.01" name="tax_percentage"
                                class="form-control @error('tax_percentage') is-invalid @enderror" placeholder="Enter tax"
                                value="{{ old('tax_percentage', $variant->tax_percentage) }}">

                            @error('tax_percentage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <label class="form-label">Status</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-shield-check-line"></i></span>

                            <select name="is_active" class="form-select @error('is_active') is-invalid @enderror">

                                <option value="1" {{ old('is_active', $variant->is_active) == 1 ? 'selected' : '' }}>
                                    ACTIVE
                                </option>

                                <option value="0" {{ old('is_active', $variant->is_active) == 0 ? 'selected' : '' }}>
                                    INACTIVE
                                </option>

                            </select>

                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                </div>

                <!-- Submit -->
                <div class="mt-4">
                    <button class="btn btn-primary">
                        <i class="ri-save-line me-1"></i>
                        {{ $variant->id ? 'Update Variant' : 'Save Variant' }}
                    </button>
                </div>

            </form>

        </div>

    </div>

@endsection
