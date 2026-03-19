@extends('admin.layouts.master')

@section('title','Create Payment Method')

@section('content')

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Create Payment Method</h5>

        <a href="{{ route('admin.payments.payment_methods') }}" class="btn btn-sm btn-light">
            <i class="ri-arrow-left-line me-1"></i> Back
        </a>
    </div>

    <div class="card-body">

        <form>
            <div class="row">

                <!-- Code -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Code</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ri-code-line"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Enter code">
                    </div>
                </div>

                <!-- Name -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Name</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ri-bank-card-line"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Enter payment method name">
                    </div>
                </div>

                <!-- Type -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Type</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ri-exchange-funds-line"></i>
                        </span>
                        <select class="form-select">
                            <option>Select Type</option>
                            <option>Online</option>
                            <option>Offline</option>
                        </select>
                    </div>
                </div>

                <!-- Icon -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Icon</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ri-image-line"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Enter icon class">
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
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

            </div>

            <div class="mt-3">
                <button class="btn btn-primary">
                    <i class="ri-save-line me-1"></i> Save Payment Method
                </button>
            </div>

        </form>

    </div>
</div>

@endsection