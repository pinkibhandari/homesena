<div class="d-flex justify-content-end mb-3">
    <form method="GET" action="{{ route('admin.users.show', $user->id) }}" class="d-flex align-items-center gap-2 flex-wrap">
        <input type="hidden" name="tab" value="slots">

        <!-- Duration -->
        <select name="slot_duration" class="form-select form-select-sm" style="width:140px;">
            <option value="">Duration</option>
            <option value="30"  {{ request('slot_duration') == '30'  ? 'selected' : '' }}>30 min</option>
            <option value="60"  {{ request('slot_duration') == '60'  ? 'selected' : '' }}>60 min</option>
            <option value="90"  {{ request('slot_duration') == '90'  ? 'selected' : '' }}>90 min</option>
            <option value="120" {{ request('slot_duration') == '120' ? 'selected' : '' }}>120 min</option>
            <option value="150" {{ request('slot_duration') == '150' ? 'selected' : '' }}>150 min</option>
        </select>

        <!-- Status -->
        <select name="slot_status" class="form-select form-select-sm" style="width:160px;">
            <option value="">Status</option>
            <option value="pending"   {{ request('slot_status') == 'pending'   ? 'selected' : '' }}>Pending</option>
            <option value="accepted"  {{ request('slot_status') == 'accepted'  ? 'selected' : '' }}>Accepted</option>
            <option value="cancelled" {{ request('slot_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            <option value="confirmed" {{ request('slot_status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
            <option value="notified"  {{ request('slot_status') == 'notified'  ? 'selected' : '' }}>Notified</option>
            <option value="ongoing"   {{ request('slot_status') == 'ongoing'   ? 'selected' : '' }}>Ongoing</option>
            <option value="completed" {{ request('slot_status') == 'completed' ? 'selected' : '' }}>Completed</option>
        </select>

        <!-- OTP Verified -->
        <select name="slot_otp_verified" class="form-select form-select-sm" style="width:160px;">
            <option value="">OTP Verified</option>
            <option value="1" {{ request('slot_otp_verified') === '1' ? 'selected' : '' }}>Yes</option>
            <option value="0" {{ request('slot_otp_verified') === '0' ? 'selected' : '' }}>No</option>
        </select>

        <!-- Payment -->
        <select name="slot_payment_status" class="form-select form-select-sm" style="width:160px;">
            <option value="">Payment</option>
            <option value="paid"    {{ request('slot_payment_status') == 'paid'    ? 'selected' : '' }}>Paid</option>
            <option value="pending" {{ request('slot_payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
        </select>

        <!-- Search Button -->
        <button class="btn btn-primary btn-sm">
            <i class="ri-search-line"></i>
        </button>

        <!-- Reset -->
        <a href="{{ route('admin.users.show', ['user' => $user->id, 'tab' => 'slots']) }}" class="btn btn-outline-secondary btn-sm">
            <i class="ri-refresh-line"></i>
        </a>
    </form>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-sm">
        <thead class="bg-label-secondary">
            <tr>
                <th>#</th>
                <th>Booking ID</th>
                <th>Expert</th>
                <th>Date</th>
                <th>Time</th>
                <th>Start</th>
                <th>End</th>
                <th>Duration</th>
                <th>OTP</th>
                <th>OTP Verified</th>
                <th>Status</th>
                <th>Price</th>
                <th>Payment</th>
                <th>Check In</th>
            </tr>
        </thead>
        <tbody>
            @forelse($slots as $slot)
                <tr>
                    <td>{{ $slots->firstItem() + $loop->index }}</td>
                    <td>{{ $slot->booking->booking_code ?? 'N/A' }}</td>
                    <td>{{ $slot->expert->name ?? 'N/A' }}</td>
                    <td>{{ $slot->date }}</td>
                    <td>{{ $slot->time }}</td>
                    <td>{{ $slot->start_time }}</td>
                    <td>{{ $slot->end_time }}</td>
                    <td>{{ $slot->duration }} (min)</td>
                    <td>{{ $slot->otp_code ?? 'N/A' }}</td>
                    <!-- OTP Verified -->
                    <td>
                        @if ($slot->otp_verified)
                            <span class="badge bg-label-success rounded-pill">Yes</span>
                        @else
                            <span class="badge bg-label-danger rounded-pill">No</span>
                        @endif
                    </td>
                    <!-- Status -->
                   <td>
                            @if ($slot->status == 'pending')
                                <span class="badge bg-label-warning rounded-pill">Pending</span>

                            @elseif($slot->status == 'accepted')
                                <span class="badge bg-label-success rounded-pill">Accepted</span>

                            @elseif($slot->status == 'cancelled')
                                <span class="badge bg-label-danger rounded-pill">Cancelled</span>

                            @elseif($slot->status == 'confirmed')
                                <span class="badge bg-label-primary rounded-pill">Confirmed</span>

                            @elseif($slot->status == 'notified')
                                <span class="badge bg-label-info rounded-pill">Notified</span>

                            @elseif($slot->status == 'ongoing')
                                <span class="badge bg-label-secondary rounded-pill">Ongoing</span>

                            @elseif($slot->status == 'completed')
                                <span class="badge bg-label-dark rounded-pill">Completed</span>

                            @else
                                <span class="badge bg-label-light rounded-pill">Unknown</span>
                            @endif
                    </td>
                    <td>₹{{ number_format($slot->price, 2) }}</td>
                    <!-- Payment -->
                    <td>
                        @if ($slot->payment_status == 'paid')
                            <span class="badge bg-label-success rounded-pill">Paid</span>
                        @else
                            <span class="badge bg-label-warning rounded-pill">Pending</span>
                        @endif
                    </td>
                    <td>{{ $slot->check_in_time ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="14" class="text-center">No Slots Found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<!-- Pagination -->
<div class="row align-items-center mt-2 px-1">
    <div class="col-md-6">
        <small class="text-muted">
            Showing {{ $slots->firstItem() ?? 0 }} to {{ $slots->lastItem() ?? 0 }} of {{ $slots->total() }} results
        </small>
    </div>
    <div class="col-md-6 d-flex justify-content-end">
        {{ $slots->links('pagination::bootstrap-5') }}
    </div>
</div>
