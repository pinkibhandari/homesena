@extends('admin.layouts.master')
@section('title', 'Booking Table')
@section('content')
    <div class="card">
        <!-- ALERT MESSAGE -->
        <div class="p-3">
            @include('admin.layouts.partials.alerts')
        </div>
        <!-- Header -->
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Booking</h5>
            <div class="d-flex align-items-center gap-3">
                <!-- Search -->
                <form method="GET" action="{{ route('admin.bookings.index') }}" class="d-flex align-items-center">
                    <div class="d-flex align-items-center">
                        <span class="me-2">Search:</span>
                        <input name="search" type="search" class="form-control form-control-sm"
                            placeholder="Search booking..." value="{{ request('search') }}" style="width:200px;">
                    </div>
                </form>
            </div>
        </div>
        <hr class="my-0">
        <!-- Show Entries -->
        <div class="row px-4 py-3 align-items-center">
        </div>
        <!-- php function -->
        @php
            function statusColor($status)
            {
                return match ($status) {
                    'pending' => 'warning',
                    'accepted' => 'info',
                    'on_the_way' => 'primary',
                    'ongoing' => 'dark',
                    'completed' => 'success',
                    'cancelled' => 'danger',
                    'partial' => 'secondary',
                    default => 'light'
                };
            }
        @endphp
        <!-- Table -->
        <div class="table-responsive px-4 pb-3">
            <table class="table table-hover align-middle table-bordered">
                <thead class="bg-label-secondary">
                    <tr>
                        <th width="60">ID</th>
                        <th>Booking Code</th>
                        <th>Booking Type</th>
                        <th>Booking Sub Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Total Amount</th>
                        <th width="120">Status</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                                  <td>{{ $bookings->firstItem() + $loop->index }}</td>
                            <td>
                                <span class="fw-semibold">{{ $booking->booking_code}}</span>
                            </td>
                            <td>{{ $booking->type }}</td>
                            <td>{{ $booking->booking_subtype }}</td>
                            <td>{{ $booking->start_date }}</td>
                            <td>{{ $booking->end_date }}</td>
                            <td>{{ $booking->total_price}}</td>
                            <td>
                                <span class="badge rounded-pill bg-label-{{ statusColor($booking->status) }}">
                                    {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                </span>
                            </td>
                              <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                                        data-bs-toggle="dropdown">
                                        <i class="ri-more-2-line"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.bookings.show', $booking->id) }}">
                                                <i class="ri-eye-line me-2"></i>
                                                View Details
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No booking found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination (Dynamic) -->
        <div class="row px-4 pb-3 align-items-center">
            {{ $bookings->links('pagination::bootstrap-5') }}
        </div>
        
@endsection