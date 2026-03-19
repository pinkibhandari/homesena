@extends('admin.layouts.master')

@section('title','Create Training Center')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Create Training Center</h5>

        <a href="{{ route('admin.training_centers.index') }}" class="btn btn-sm btn-light">
            <i class="ri-arrow-left-line me-1"></i> Back
        </a>
    </div>

    <div class="card-body">

        <form>
            <div class="row">

                <!-- Center Name -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Center Name</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ri-building-line"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Enter center name">
                    </div>
                </div>

                <!-- City -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">City</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ri-map-pin-line"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Enter city">
                    </div>
                </div>

                <!-- Address -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Address</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ri-map-line"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Enter address">
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
                            <option selected disabled>Select status</option>
                            <option value="ACTIVE">ACTIVE</option>
                            <option value="INACTIVE">INACTIVE</option>
                        </select>
                    </div>
                </div>

            </div>

            <div class="mt-3">
                <button class="btn btn-primary">
                    <i class="ri-save-line me-1"></i> Save Center
                </button>
            </div>

        </form>

    </div>
</div>

@endsection