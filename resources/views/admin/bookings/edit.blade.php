@extends('admin.layouts.master')

@section('title','Create Booking')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit Booking</h5>
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-light">
            <i class="ri-arrow-left-line me-1"></i> Back
        </a>
    </div>

    <div class="card-body">

        <form>
            <div class="row">

                <!-- Booking Code -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Booking Code</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-file-copy-line"></i></span>
                        <input type="text" class="form-control" placeholder="Enter booking code" value="BK260225UR">
                    </div>
                </div>

                <!-- User -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">User</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-user-line"></i></span>
                        <select class="form-select">
                            <option>User 1</option>
                            <option selected>User 2</option>
                        </select>
                    </div>
                </div>

                <!-- Service -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Service</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-customer-service-2-line"></i></span>
                        <select class="form-select">
                            <option selected>Home Cleaning</option>
                            <option>Plumbing</option>
                        </select>
                    </div>
                </div>

                <!-- Address -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-map-pin-line"></i></span>
                        <select class="form-select">
                            <option>Address 1</option>
                            <option selected>Address 2</option>
                        </select>
                    </div>
                </div>

                <!-- Status -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Status</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-shield-check-line"></i></span>
                        <select class="form-select">
                            <option selected>Pending</option>
                            <option>Partial</option>
                            <option>Completed</option>
                            <option>Cancelled</option>
                        </select>
                    </div>
                </div>

                <!-- Booking Type -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Booking Type</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-list-check"></i></span>
                        <select class="form-select">
                            <option selected>Single</option>
                            <option>Multiple</option>
                            <option>Custom</option>
                        </select>
                    </div>
                </div>

                <!-- Total Amount -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Total Amount</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-money-dollar-circle-line"></i></span>
                        <input type="number" class="form-control" placeholder="Enter total amount" step="0.01" value="1500.00">
                    </div>
                </div>

                <!-- Booking Date -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Booking Date</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-calendar-line"></i></span>
                        <input type="date" class="form-control" value="2026-03-16">
                    </div>
                </div>

                <!-- Notes -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Notes</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-file-text-line"></i></span>
                        <textarea class="form-control" rows="2">Some notes about the booking</textarea>
                    </div>
                </div>

            </div>

            <div class="mt-3">
                <button class="btn btn-primary">
                    <i class="ri-save-line me-1"></i> Update Booking
                </button>
            </div>

        </form>

    </div>
</div>
@endsection