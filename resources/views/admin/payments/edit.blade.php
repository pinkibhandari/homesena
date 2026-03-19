@extends('admin.layouts.master')

@section('title','Edit Payment')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit Payment</h5>

        <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-light">
            <i class="ri-arrow-left-line me-1"></i> Back
        </a>
    </div>

    <div class="card-body">

        <form>
            <div class="row">

                <!-- Booking Slot -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Booking Slot ID</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ri-time-line"></i>
                        </span>
                        <input type="text"
                               class="form-control"
                               value="12"
                               placeholder="Enter booking slot id">
                    </div>
                </div>

                <!-- Booking -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Booking ID</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ri-file-list-line"></i>
                        </span>
                        <input type="text"
                               class="form-control"
                               value="BK1021"
                               placeholder="Enter booking id">
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Payment Method</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ri-bank-card-line"></i>
                        </span>

                        <select class="form-select">
                            <option selected>Razorpay</option>
                            <option>Stripe</option>
                            <option>Cash</option>
                        </select>

                    </div>
                </div>

                <!-- Gateway Order ID -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Gateway Order ID</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ri-barcode-line"></i>
                        </span>
                        <input type="text"
                               class="form-control"
                               value="ORD12345"
                               placeholder="Enter gateway order id">
                    </div>
                </div>

                <!-- Gateway Payment ID -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Gateway Payment ID</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ri-secure-payment-line"></i>
                        </span>
                        <input type="text"
                               class="form-control"
                               value="PAY12345"
                               placeholder="Enter gateway payment id">
                    </div>
                </div>

                <!-- Gateway Signature -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Gateway Signature</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ri-shield-keyhole-line"></i>
                        </span>
                        <input type="text"
                               class="form-control"
                               value="signature123"
                               placeholder="Enter gateway signature">
                    </div>
                </div>

                <!-- Amount -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Amount</label>
                    <div class="input-group">
                        <span class="input-group-text">₹</span>
                        <input type="number"
                               step="0.01"
                               class="form-control"
                               value="1500"
                               placeholder="Enter amount">
                    </div>
                </div>

                <!-- Currency -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Currency</label>
                    <div class="input-group">
                        <span class="input-group-text">₹</span>
                        <input type="text"
                               class="form-control"
                               value="INR"
                               placeholder="Enter currency">
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
                            <option>Pending</option>
                            <option selected>Paid</option>
                            <option>Failed</option>
                        </select>

                    </div>
                </div>

                <!-- Paid At -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Paid At</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ri-calendar-line"></i>
                        </span>
                        <input type="datetime-local"
                               class="form-control">
                    </div>
                </div>

            </div>

            <div class="mt-3">
                <button class="btn btn-primary">
                    <i class="ri-save-line me-1"></i> Update Payment
                </button>
            </div>

        </form>

    </div>
</div>

@endsection