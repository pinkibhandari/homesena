@extends('admin.layouts.master')
@section('title', 'User Details')

@section('content')
<div class="card">

    <!-- ALERT MESSAGE -->
    <div class="p-3">
        @include('admin.layouts.partials.alerts')
    </div>

    <!-- Header -->
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">User Details</h5>

        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary">
            <i class="ri-arrow-left-line me-1"></i> Back
        </a>
    </div>

    <hr class="my-0">

    <!-- User Info -->
    <div class="row px-4 py-3">
        <div class="col-md-2">
            <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('assets/img/default-profile-image.jpg') }}"
                width="80" height="80" style="border-radius:50%;">
        </div>

        <div class="col-md-10">
            <div class="row">
                <div class="col-md-4 mb-2">
                    <strong>Name:</strong> {{ $user->name }}
                </div>
                <div class="col-md-4 mb-2">
                    <strong>Email:</strong> {{ $user->email }}
                </div>
                <div class="col-md-4 mb-2">
                    <strong>Phone:</strong> {{ $user->phone }}
                </div>

                <div class="col-md-4 mb-2">
                    <strong>Profile Completed:</strong>
                    @if ($user->profile_completed === 1)
                        <span class="badge bg-label-primary rounded-pill">Yes</span>
                    @else
                        <span class="badge bg-label-secondary rounded-pill">No</span>
                    @endif
                </div>

                <div class="col-md-4 mb-2">
                    <strong>Status:</strong>
                    @if ($user->status === 'ACTIVE')
                        <span class="badge bg-label-success rounded-pill">Active</span>
                    @else
                        <span class="badge bg-label-danger rounded-pill">Inactive</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <hr class="my-0">

    <!-- Address Table -->
    <div class="card-header">
        <h6 class="mb-0">User Addresses</h6>
    </div>

    <div class="table-responsive px-4 pb-3">
        <table class="table table-hover align-middle table-bordered">
            <thead class="bg-label-secondary">
                <tr>
                    <th>#</th>
                    <th>Flat No</th>
                    <th>Address</th>
                    <th>Landmark</th>
                    <th>Save As</th>
                    <th>Pets</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Accuracy</th>
                </tr>
            </thead>
            <tbody>
                @forelse($addresses as $key => $address)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $address->flat_no }}</td>
                        <td>{{ $address->address }}</td>
                        <td>{{ $address->landmark }}</td>
                        <td>{{ ucfirst($address->save_as) }}</td>
                        <td>{{ $address->pets ?? 'N/A' }}</td>
                        <td>{{ $address->address_lat }}</td>
                        <td>{{ $address->address_long }}</td>
                        <td>{{ $address->accuracy }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">No Addresses Found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection