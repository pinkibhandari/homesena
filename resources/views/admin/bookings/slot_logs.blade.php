@extends('admin.layouts.master')

@section('title', 'Slot Logs')

@section('content')

<div class="card">
    
    <!-- Header -->
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Slot Logs</h5>
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
                        <th>Action</th>
                        <th>Reason</th>
                        <th>Created AT</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($logs as $key => $log)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $log->expert->name ?? 'N/A' }}</td>
                            <td>{{ $log->booking_slot_id }}</td>
                            <td>
                                @if($log->action == 'accepted')
                                    <span class="badge bg-success">Accepted</span>
                                @else
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>

                            <td>{{ $log->reason ?? '-' }}</td>

                            <td>{{ $log->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No Logs Found</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
</div>

@endsection