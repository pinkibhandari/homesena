@extends('admin.layouts.master')
@section('title', $expert->id ? 'Edit Expert' : 'Create Expert')
@section('content')
    <div class="card">
         <!-- ALERT MESSAGE -->
    <div class="p-3">
        @include('admin.layouts.partials.alerts')
    </div>
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                {{ $expert->id ? 'Edit Expert' : 'Create Expert' }}
            </h5>
            <a href="{{ route('admin.experts.index') }}" class="btn btn-sm btn-light">
                <i class="ri-arrow-left-line me-1"></i> Back
            </a>
        </div>
        <div class="card-body">
            <form method="POST"
                action="{{ $expert->id ? route('admin.experts.update', $expert->id) : route('admin.experts.store') }}">
                @csrf
                @if ($expert->id)
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
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                placeholder="Enter name" value="{{ old('name', $expert->name) }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- ---------------- -->
                    <!-- Phone -->
                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <label class="form-label">Phone</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-phone-line"></i>
                            </span>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                placeholder="Enter phone" value="{{ old('phone', $expert->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <!-- Email -->
                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <label class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-mail-line"></i>
                            </span>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                placeholder="Enter email" value="{{ old('email', $expert->email) }}">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <!-- password -->
                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-mail-line"></i>
                            </span>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" placeholder="Enter password">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        @if($expert->exists)
                            <small class="text-muted">Leave blank to keep old password</small>
                        @endif
                    </div>

                    @if(!$expert->exists)
                        <!-- Device ID -->
                        <div class="col-lg-4 col-md-6 col-12 mb-3">
                            <label class="form-label">Device ID</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="ri-fingerprint-line"></i>
                                </span>
                                <input type="text" class="form-control @error('device_id') is-invalid @enderror"
                                    name="device_id" placeholder="Enter device ID"
                                    value="{{ old('device_id', $expert->device_id) }}">
                                @error('device_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <!-- Device Type -->
                        <div class="col-lg-4 col-md-6 col-12 mb-3">
                            <label class="form-label">Device Type</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="ri-smartphone-line"></i>
                                </span>
                                <select name="device_type" class="form-select @error('device_type') is-invalid @enderror">
                                    <option selected disabled>Select device</option>
                                    <option value="android" {{ old('device_type', $expert->device_type) == 'android' ? 'selected' : '' }}>
                                                Android</option>
                                    <option value="ios" {{ old('device_type', $expert->device_type) == 'ios' ? 'selected' : '' }}>
                                        IOS</option>
                                    <!-- <option>Web</option> -->
                                </select>
                                @error('device_type')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    @endif
                    <!-- Registration Code -->
                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <label class="form-label">Registration Code</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-barcode-line"></i>
                            </span>
                            <input type="text" name="registration_code"
                                class="form-control @error('registration_code') is-invalid @enderror"
                                placeholder="Enter registration code"
                                value="{{ old('registration_code', $expert->expertDetail?->registration_code) }}">
                            @error('registration_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- Onboarding Agent -->
                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <label class="form-label">Onboarding Agent</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-user-star-line"></i>
                            </span>
                            <input type="text" name="onboarding_agent_code"
                                class="form-control @error('onboarding_agent_code') is-invalid @enderror"
                                placeholder="Enter agent code"
                                value="{{ old('onboarding_agent_code', $expert->expertDetail?->onboarding_agent_code) }}">
                            @error('onboarding_agent_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- Work Schedule -->
                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <label class="form-label">Work Schedule</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-calendar-line"></i>
                            </span>
                            <select class="form-select @error('work_schedule') is-invalid @enderror" name="work_schedule">
                                <option selected disabled>Select Work Schedule</option>
                                <option value="weekend only" {{ old('work_schedule',  $expert->expertDetail?->work_schedule) == 'weekend only' ? 'selected' : '' }}>
                                    Weekend Only
                                </option>
                                <option value="weekdays" {{ old('work_schedule',  $expert->expertDetail?->work_schedule) == 'weekdays' ? 'selected' : '' }}>
                                    Weekdays
                                </option>
                                <option value="night shift" {{ old('work_schedule',  $expert->expertDetail?->work_schedule) == 'night shift' ? 'selected' : '' }}>
                                    Night Shift
                                </option>
                                <option value="anytime" {{ old('work_schedule',  $expert->expertDetail?->work_schedule) == 'anytime' ? 'selected' : '' }}>
                                    Anytime
                                </option>
                            </select>
                            @error('work_schedule')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- training center -->
                      <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <label class="form-label">Training Center</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-calendar-line"></i>
                            </span>
                         <select class="form-select @error('training_center_id') is-invalid @enderror" name="training_center_id">
                        <option selected disabled>Select Training Center</option>

                        @foreach($trainingCenters as $center)
                            <option value="{{ $center->id }}" {{ old('training_center_id', $expert->expertDetail?->training_center_id ?? '') == $center->id ? 'selected' : '' }}>
                                {{ $center->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('work_schedule')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    </div>
                    </div>
                         <!-- Status -->
                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <label class="form-label">Status</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-shield-check-line"></i>
                            </span>
                            <select class="form-select @error('status') is-invalid @enderror" name="status">
                                <option selected disabled>Select status</option>
                                <option value="ACTIVE" {{ old('status', $expert->status) == 'ACTIVE' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="INACTIVE" {{ old('status', $expert->status) == 'INACTIVE' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <!--  -->
                     <!-- <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="is_online" value="1"
                                {{ old('is_online', $expert->is_online ?? 0) == 1 ? 'checked' : '' }}>
                            <label class="form-check-label">Online</label>
                        </div>
                  </div> -->
                  <!-- Status Toggle -->
                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <label class="form-label">Is Online</label>
                        <!-- Hidden input (for OFF value) -->
                        <input type="hidden" name="is_online" value="0">
                        <div class="form-control d-flex align-items-center">
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input @error('is_online') is-invalid @enderror" type="checkbox" id="statusToggle"
                                    name="is_online" value="1" {{ old('is_online', $expert->expertDetail->is_online ?? '') == '1' ? 'checked' : '' }}>
                            </div>
                        </div>
                        @error('is_online')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <!--  -->
                 @if(!$expert->exists)
                     <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                          Emergency Contacts
                        </h5>
                    </div>
                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <label class="form-label">Name</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-user-line"></i>
                            </span>
                            <input type="text" name="emergency_contact_name" class="form-control @error('emergency_contact_name') is-invalid @enderror"
                                placeholder="Enter user id" value="{{ old('emergency_contact_name', $expert->Name) }}">
                            @error('emergency_contact_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                     <!-- Phone -->
                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <label class="form-label">Phone</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-phone-line"></i>
                            </span>
                            <input type="text" name="emergency_contact_phone" class="form-control @error('emergency_contact_phone') is-invalid @enderror"
                                placeholder="Enter phone" value="{{ old('emergency_contact_phone', $expert->phone) }}">
                            @error('emergency_contact_phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    @endif
                    <!--  -->
                </div>
                <div class="mt-3">
                    <button class="btn btn-primary">
                        <i class="ri-save-line me-1"></i>
                        {{ $expert->id ? 'Update' : 'Save' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection