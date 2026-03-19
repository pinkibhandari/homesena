@extends('admin.layouts.master')

@section('title','Edit Expert')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit Expert</h5>

        <a href="{{ route('admin.experts.index') }}" class="btn btn-sm btn-light">
            <i class="ri-arrow-left-line me-1"></i> Back
        </a>
    </div>

    <div class="card-body">
        <form>

            <div class="row">

                <!-- User -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">User</label>
                    <select class="form-select">
                        <option selected disabled>Select User</option>
                        <option value="101" selected>User 101</option>
                        <option value="102">User 102</option>
                    </select>
                </div>

                <!-- Registration Code -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Registration Code</label>
                    <input type="text" class="form-control" value="REG-EX101" placeholder="Enter registration code">
                </div>

                <!-- Onboarding Agent Code -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Onboarding Agent Code</label>
                    <input type="text" class="form-control" value="AGENT-01" placeholder="Enter agent code">
                </div>

                <!-- Training Center -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Training Center</label>
                    <select class="form-select">
                        <option selected disabled>Select Center</option>
                        <option value="1" selected>Center 1</option>
                        <option value="2">Center 2</option>
                    </select>
                </div>

                <!-- Work Schedule -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Work Schedule</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-calendar-line"></i></span>
                        <input type="date" class="form-control" value="2026-03-16">
                    </div>
                </div>

            </div>

            <div class="mt-3">
                <button class="btn btn-primary">
                    <i class="ri-save-line me-1"></i> Update Expert
                </button>
            </div>

        </form>
    </div>
</div>

@endsection