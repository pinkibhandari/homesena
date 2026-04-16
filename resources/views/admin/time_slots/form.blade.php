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

                        <!-- Visible Time (12hr) -->
                        <input type="text" id="display_time"
                            class="form-control"
                            placeholder="HH:MM"
                            value="{{ old('start_time', $slot->start_time ? \Carbon\Carbon::parse($slot->start_time)->format('h:i') : '') }}">

                        <!-- AM / PM -->
                        <select id="time_period" class="form-select" style="max-width: 90px;">
                            <option value="AM"
                                {{ old('start_time', $slot->start_time ? \Carbon\Carbon::parse($slot->start_time)->format('A') : '') == 'AM' ? 'selected' : '' }}>
                                AM
                            </option>
                            <option value="PM"
                                {{ old('start_time', $slot->start_time ? \Carbon\Carbon::parse($slot->start_time)->format('A') : '') == 'PM' ? 'selected' : '' }}>
                                PM
                            </option>
                        </select>

                        <!-- Hidden Actual Input -->
                        <input type="hidden" name="start_time" id="start_time"
                            value="{{ old('start_time', $slot->start_time ? \Carbon\Carbon::parse($slot->start_time)->format('H:i') : '') }}">

                        @error('start_time')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <div class="col-lg-4 col-md-6 col-12">
                    <label class="form-label">Status</label>

                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ri-shield-check-line"></i>
                        </span>

                        <select name="status"
                            class="form-select @error('status') is-invalid @enderror">

                            <option value="1"
                                {{ old('status', $slot->status ?? 1) == 1 ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="0"
                                {{ old('status', $slot->status ?? 1) == 0 ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                        @error('status')
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

<!--  JS for Time Conversion -->
<script>
    function convertTo24Hour(time, period) {
        if (!time.includes(':')) return '';

        let [hours, minutes] = time.split(':');
        hours = parseInt(hours);

        if (period === 'PM' && hours < 12) hours += 12;
        if (period === 'AM' && hours === 12) hours = 0;

        return String(hours).padStart(2, '0') + ':' + minutes;
    }

    function updateHiddenTime() {
        let time = document.getElementById('display_time').value;
        let period = document.getElementById('time_period').value;

        let converted = convertTo24Hour(time, period);

        if (converted) {
            document.getElementById('start_time').value = converted;
        }
    }

    document.getElementById('display_time').addEventListener('keyup', updateHiddenTime);
    document.getElementById('time_period').addEventListener('change', updateHiddenTime);
</script>

@endsection