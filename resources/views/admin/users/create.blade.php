@extends('admin.layouts.master')

@section('title', 'Create User')

@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Create User</h5>

            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-light">
                <i class="ri-arrow-left-line me-1"></i> Back
            </a>
        </div>

        <div class="card-body">

            <form>
                <div class="row">

                    <!-- Name -->
                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <label class="form-label">Name</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-user-line"></i>
                            </span>
                            <input type="text" name="name" class="form-control" placeholder="Enter name">
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <label class="form-label">Phone</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-phone-line"></i>
                            </span>
                            <input type="text" name="phone" class="form-control" placeholder="Enter phone">
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <label class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-mail-line"></i>
                            </span>
                            <input type="email" name="email" class="form-control" placeholder="Enter email">
                        </div>
                    </div>
                    <!-- password -->
                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-mail-line"></i>
                            </span>
                            <input type="email" name="password" class="form-control" placeholder="Enter password">
                        </div>
                    </div>
                    <!-- Device ID -->
                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <label class="form-label">Device ID</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-fingerprint-line"></i>
                            </span>
                            <input type="text" class="form-control" name="device_id" placeholder="Enter device ID">
                        </div>
                    </div>

                    <!-- Device Type -->
                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <label class="form-label">Device Type</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-smartphone-line"></i>
                            </span>
                            <select name="device_type" class="form-select" >
                                <option selected disabled>Select device</option>
                                <option value="android" {{ request('device')=='android' ? 'selected' : '' }}>Android</option>
                                <option value="ios" {{ request('device')=='ios' ? 'selected' : '' }}>IOS</option>
                                <!-- <option>Web</option> -->
                            </select>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <label class="form-label">Status</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-shield-check-line"></i>
                            </span>
                            <select class="form-select" name="status">
                                <option selected disabled>Select status</option>
                                <option value="ACTIVE" {{ request('status')=='ACTIVE' ? 'selected' : '' }}>Active</option>
                                <option value="INACTIVE" {{ request('status')=='INACTIVE' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                </div>

                <div class="mt-3">
                    <button class="btn btn-primary">
                        <i class="ri-save-line me-1"></i> Save User
                    </button>
                </div>

            </form>

        </div>
    </div>

@endsection