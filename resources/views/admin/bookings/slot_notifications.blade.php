@extends('admin.layouts.master')

@section('title', 'Slot Notifications')

@section('content')

<div class="card">
    
    <!-- Header -->
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Slot Notifications</h5>
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-secondary">
            <i class="ri-arrow-left-line"></i> Back
        </a>
    </div>

    <!-- Body -->
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Expert</th>
                        <th>Booking Slot ID</th>
                        <th>Sent At</th>
                        <th>Created AT</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($notifications as $key => $notification)
                        <tr>
                            <td>{{ $key + 1 }}</td>

                            <td>{{ $notification->booking_slot_id }}</td>

                            <td>{{ $notification->sent_at ?? '-' }}</td>


                            <td>{{ $notification->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No Notifications Found</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
</div>

@endsection