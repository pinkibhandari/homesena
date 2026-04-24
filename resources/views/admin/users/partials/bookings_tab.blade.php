<div class="d-flex justify-content-end mb-3">
    <form method="GET" action="{{ route('admin.users.show', $user->id) }}" class="d-flex align-items-center gap-2 flex-wrap">
        <input type="hidden" name="tab" value="bookings">
        <!--  Type -->
        <select name="type" class="form-select form-select-sm" style="width:140px;">
            <option value="">Type</option>
            <option value="scheduled" {{ request('type') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
            <option value="instant" {{ request('type') == 'instant' ? 'selected' : '' }}>Instant</option>
        </select>

        <!--  Sub Type -->
        <select name="sub_type" class="form-select form-select-sm" style="width:150px;">
            <option value="">Sub Type</option>
            <option value="recurring" {{ request('sub_type') == 'recurring' ? 'selected' : '' }}>Recurring</option>
            <option value="single" {{ request('sub_type') == 'single' ? 'selected' : '' }}>Single</option>
        </select>

        <!--  Status -->
        <select name="status" class="form-select form-select-sm" style="width:160px;">
            <option value="">Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            <option value="complete" {{ request('status') == 'complete' ? 'selected' : '' }}>Complete</option>
        </select>

        <!--  Payment Status -->
        <select name="payment_status" class="form-select form-select-sm" style="width:160px;">
            <option value="">Payment Status</option>
            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
        </select>

        <!-- Button -->
        <button class="btn btn-primary btn-sm">
            <i class="ri-search-line"></i>
        </button>

        <!--  Reset -->
        <a href="{{ route('admin.users.show', ['user' => $user->id, 'tab' => 'bookings']) }}" class="btn btn-outline-secondary btn-sm">
            <i class="ri-refresh-line"></i>
        </a>
    </form>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-sm">
        <thead class="bg-label-secondary">
            <tr>
                <th>#</th>
                <th>Booking Code</th>
                <th>Service</th>
                <th>Type</th>
                <th>Subtype</th>
                <th>Time</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Payment Status</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
                <tr>
                    <td>{{ $bookings->firstItem() + $loop->index }}</td>
                    <td>{{ $booking->booking_code }}</td>
                    <td>{{ $booking->service->name ?? 'N/A' }}</td>
                    <td>
                        <span class="badge bg-label-info rounded-pill">
                            {{ ucfirst($booking->type) }}
                        </span>
                    </td>
                    <td>{{ ucfirst($booking->booking_subtype) }}</td>
                    <td>{{ $booking->time ?? 'N/A' }}</td>
                    <td>{{ $booking->start_date ?? 'N/A' }}</td>
                    <td>{{ $booking->end_date ?? 'N/A' }}</td>
                    <td>₹{{ number_format($booking->total_price, 2) }}</td>
                    <!-- Status -->
                    <td>
                        @if ($booking->status == 'pending')
                            <span class="badge bg-label-warning rounded-pill">Pending</span>
                        @elseif($booking->status == 'accepted')
                            <span class="badge bg-label-primary rounded-pill">Accepted</span>
                        @elseif($booking->status == 'ongoing')
                            <span class="badge bg-label-info rounded-pill">Ongoing</span>
                        @elseif($booking->status == 'completed')
                            <span class="badge bg-label-success rounded-pill">Completed</span>
                        @else
                            <span class="badge bg-label-danger rounded-pill">Cancelled</span>
                        @endif
                    </td>
                    <!-- Payment Status -->
                    <td>
                        @if ($booking->payment_status == 'paid')
                            <span class="badge bg-label-success rounded-pill">Paid</span>
                        @elseif($booking->payment_status == 'pending')
                            <span class="badge bg-label-warning rounded-pill">Pending</span>
                        @else
                            <span class="badge bg-label-danger rounded-pill">Failed</span>
                        @endif
                    </td>
                    <td>{{ $booking->booking_created_at }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center">No Bookings Found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<!-- Pagination -->
<div class="row align-items-center mt-2 px-1">
    <div class="col-md-6">
        <small class="text-muted">
            Showing {{ $bookings->firstItem() ?? 0 }} to {{ $bookings->lastItem() ?? 0 }} of {{ $bookings->total() }} results
        </small>
    </div>
    <div class="col-md-6 d-flex justify-content-end">
        {{ $bookings->links('pagination::bootstrap-5') }}
    </div>
</div>
