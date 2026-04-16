@extends('admin.layouts.master')

@section('title', isset($setting) ? 'Edit Refer & Earn Settings' : 'Add Refer & Earn Settings')

@section('content')

<div class="card">

    <!-- Header -->
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            {{ isset($setting) ? 'Edit Refer & Earn Settings' : 'Create Refer & Earn Settings' }}
        </h5>

        <a href="{{ route('admin.refer_earn_settings.index') }}" class="btn btn-secondary btn-sm">
            <i class="ri-arrow-left-line me-1"></i> Back
        </a>
    </div>

    <div class="card-body">

        @include('admin.layouts.partials.alerts')

        @if(isset($setting))
            <form method="POST" action="{{ route('admin.refer_earn_settings.update', $setting->id) }}">
                @csrf
                @method('PUT')
        @else
            <form method="POST" action="{{ route('admin.refer_earn_settings.store') }}">
                @csrf
        @endif

        <div class="row">

            <!-- You Get -->
            <div class="col-md-6 mb-3">
                <label class="form-label">You Get (Coins 🪙)</label>
                <input type="number" name="referral_amount"
                    value="{{ old('referral_amount', $setting->referral_amount ?? '') }}"
                    class="form-control" placeholder="Enter coins" required>
            </div>

            <!-- They Get -->
            <div class="col-md-6 mb-3">
                <label class="form-label">They Get (Coins 🪙)</label>
                <input type="number" name="signup_bonus"
                    value="{{ old('signup_bonus', $setting->signup_bonus ?? '') }}"
                    class="form-control" placeholder="Enter coins" required>
            </div>

           

        </div>

        <!-- Submit -->
        <button type="submit" class="btn btn-primary">
            {{ isset($setting) ? 'Update' : 'Save' }}
        </button>

        </form>

    </div>
</div>

@endsection