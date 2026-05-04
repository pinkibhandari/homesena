@extends('admin.layouts.master')

@section('title', 'Assign Expert')

@section('content')

    <div class="card">

        <!-- Header -->
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Assign Expert</h5>
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-secondary">
                Back
            </a>
        </div>

        <div class="card-body">
            <!-- Assign Form -->
            <form method="POST" action="{{ route('admin.bookings.assignExpertSubmit', $booking->id) }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Select Expert</label>
                    <select name="expert_id" class="form-select @error('expert_id') is-invalid @enderror" required>

                        <option value="">-- Select Expert --</option>

                        @foreach ($experts as $expert)
                            <option value="{{ $expert->id }}" {{ $booking->expert_id == $expert->id ? 'selected' : '' }}>

                                {{ $expert->name }} ({{ $expert->phone }})
                                <span
                                    class="badge {{ $expert->expertDetail?->is_online ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $expert->expertDetail?->is_online ? 'Online' : 'Offline' }}
                                </span>
                            </option>
                        @endforeach

                    </select>

                    @error('expert_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Assign Expert
                    </button>
                </div>

            </form>

        </div>
    </div>

@endsection
