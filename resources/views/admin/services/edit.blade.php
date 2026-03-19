@extends('admin.layouts.master')

@section('title','Edit Service')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit Service</h5>

        <a href="{{ route('admin.services.index') }}" class="btn btn-sm btn-light">
            <i class="ri-arrow-left-line me-1"></i> Back
        </a>
    </div>

    <div class="card-body">

        <form>
            <div class="row">

                <!-- Service Name -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Service Name</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ri-service-line"></i>
                        </span>
                        <input type="text" class="form-control" value="Home Cleaning">
                    </div>
                </div>

                <!-- Image -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Service Image</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ri-image-line"></i>
                        </span>
                        <input type="file" class="form-control">
                    </div>
                </div>

                <!-- Status -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Status</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ri-shield-check-line"></i>
                        </span>
                        <select class="form-select">
                            <option selected>ACTIVE</option>
                            <option>INACTIVE</option>
                        </select>
                    </div>
                </div>

                <!-- Description -->
                <div class="col-4 mb-2">
                    <label class="form-label">Description</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ri-file-text-line"></i>
                        </span>
                        <textarea class="form-control" rows="2">Professional home cleaning service.</textarea>
                    </div>
                </div>

            </div>

            <div class="mt-3">
                <button class="btn btn-primary">
                    <i class="ri-save-line me-1"></i> Update Service
                </button>
            </div>

        </form>

    </div>
</div>

@endsection