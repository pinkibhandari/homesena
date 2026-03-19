@extends('admin.layouts.master')
@section('title', $user->id ? 'Edit User' : 'Create User')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ $user->id ? 'Edit User' : 'Create User' }}</h5>
            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-light">
                <i class="ri-arrow-left-line me-1"></i> Back
            </a>
        </div>
        <div class="card-body">
            <form method="POST"
                action="{{ $user->id ? route('admin.users.update', $user->id) : route('admin.users.store') }}">
                @csrf
                @if($user->id)
                    @method('PUT')
                @endif
                <div class="row">
                    <!-- Name -->
                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <label class="form-label">Name</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-user-line"></i>
                            </span>
                            <input type="text" name="name" class="form-control" placeholder="Enter name"
                                value="{{ old('name', $user->name) }}">
                        </div>
                    </div>
                    <!-- Phone -->
                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <label class="form-label">Phone</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-phone-line"></i>
                            </span>
                            <input type="text" name="phone" class="form-control" placeholder="Enter phone"
                                value="{{ old('phone', $user->phone) }}">
                        </div>
                    </div>
                    <!-- Email -->
                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <label class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-mail-line"></i>
                            </span>
                            <input type="email" name="email" class="form-control" placeholder="Enter email"
                                value="{{ old('email', $user->email) }}">
                        </div>
                    </div>
                    <!-- password -->
                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-mail-line"></i>
                            </span>
                            <input type="password" name="password" class="form-control" placeholder="Enter password">
                           
                        </div>
                         @if($user->exists)
                                <small class="text-muted">Leave blank to keep old password</small>
                          @endif
                    </div>

                   @if(!$user->exists)
                    <!-- Device ID -->
                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <label class="form-label">Device ID</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-fingerprint-line"></i>
                            </span>
                            <input type="text" class="form-control" name="device_id" placeholder="Enter device ID"
                                value="{{ old('device_id', $user->device_id) }}">
                        </div>
                    </div>
                    <!-- Device Type -->
                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <label class="form-label">Device Type</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-smartphone-line"></i>
                            </span>
                            <select name="device_type" class="form-select">
                                <option selected disabled>Select device</option>
                                <option value="android" {{ old('device_type', $user->device_type) == 'android' ? 'selected' : '' }}>
                                    Android
                                </option>
                                <option value="ios" {{ old('device_type', $user->device_type) == 'ios' ? 'selected' : '' }}>IOS</option>
                                <!-- <option>Web</option> -->
                            </select>
                        </div>
                    </div>
                    @endif
                    <!-- Status -->
                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <label class="form-label">Status</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-shield-check-line"></i>
                            </span>
                            <select class="form-select" name="status">
                                <option selected disabled>Select status</option>
                                <option value="ACTIVE" {{ old('status', $user->status) == 'ACTIVE' ? 'selected' : '' }}>Active
                                </option>
                                <option value="INACTIVE" {{ old('status', $user->status) == 'INACTIVE' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <button class="btn btn-primary">
                        <i class="ri-save-line me-1"></i> {{ $user->id ? 'Update' : 'Save' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection