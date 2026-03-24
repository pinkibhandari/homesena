@extends('admin.layouts.master')

@section('title', $slot->id ? 'Edit Time Slot' : 'Create Time Slot')

@section('content')

<div class="card">

    <!-- Header -->
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            {{ $slot->id ? 'Edit Time Slot' : 'Create Time Slot' }}
        </h5>

        <a href="{{ route('admin.time_slots.index') }}" class="btn btn-sm btn-light">
            <i class="ri-arrow-left-line me-1"></i> Back
        </a>
    </div>

    <div class="card-body">

        <form method="POST"
            action="{{ $slot->id 
                ? route('admin.time_slots.update', $slot->id) 
                : route('admin.time_slots.store') }}">

            @csrf
            @if($slot->id)
                @method('PUT')
            @endif

            <div class="row g-3">

                <!-- Time -->
                <div class="col-lg-4 col-md-6 col-12">
                    <label class="form-label">Time</label>

                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ri-time-line"></i>
                        </span>

                        <input type="time" name="start_time"
                            class="form-control @error('start_time') is-invalid @enderror"
                            value="{{ old('start_time', $slot->start_time) }}">

                        @error('start_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

            </div>

            <div class="mt-4">
                <button class="btn btn-primary">
                    <i class="ri-save-line me-1"></i>
                    {{ $slot->id ? 'Update Slot' : 'Save Slot' }}
                </button>
            </div>

        </form>

    </div>

</div>

@endsection