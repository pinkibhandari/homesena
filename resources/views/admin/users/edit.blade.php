@extends('admin.layouts.master')

@section('title','Edit User')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit User</h5>

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
                        <input type="text" class="form-control" value="Rahul Sharma">
                    </div>
                </div>

                <!-- Phone -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Phone</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ri-phone-line"></i>
                        </span>
                        <input type="text" class="form-control" value="9876543210">
                    </div>
                </div>

                <!-- Email -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ri-mail-line"></i>
                        </span>
                        <input type="email" class="form-control" value="rahul@gmail.com">
                    </div>
                </div>

                <!-- Device ID -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Device ID</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ri-fingerprint-line"></i>
                        </span>
                        <input type="text" class="form-control" value="DEV123456">
                    </div>
                </div>

                <!-- Device Type -->
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <label class="form-label">Device Type</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ri-smartphone-line"></i>
                        </span>
                        <select class="form-select">
                            <option selected>Android</option>
                            <option>IOS</option>
                            <option>Web</option>
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

                        <select class="form-select">
                            <option selected>Active</option>
                            <option>Inactive</option>
                        </select>
                    </div>
                </div>

            </div>

            <div class="mt-3">
                <button class="btn btn-primary">
                    <i class="ri-save-line me-1"></i> Update User
                </button>
            </div>

        </form>

    </div>
</div>

@endsection