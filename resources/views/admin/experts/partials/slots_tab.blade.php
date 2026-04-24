<div class="d-flex justify-content-end mb-3">
    <form method="GET" action="{{ route('admin.experts.show', $expert->id) }}" class="d-flex align-items-center gap-2 flex-wrap">
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
        <a href="{{ route('admin.experts.show', ['expert' => $expert->id, 'tab' => 'slots']) }}" class="btn btn-outline-secondary btn-sm">
            <i class="ri-refresh-line"></i>
        </a>
    </form>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>#</th>
                <th>Expert Name</th>
                <th>Date</th>
                <th>Start</th>
                <th>End</th>
                <th>Duration</th>
                <th>Price</th>
                <th>Status</th>
                <th>Payment</th>
                <th>OTP</th>
            </tr>
        </thead>
        <tbody>
            @forelse($slots as $slot)
                <tr>
                    <td>{{ $slots->firstItem() + $loop->index }}</td>
                    <td>{{ $slot->expert->name ?? '-' }}</td>
                    <td>{{ $slot->date ?? '-' }}</td>
                    <td>{{ $slot->start_time ?? '-' }}</td>
                    <td>{{ $slot->end_time ?? '-' }}</td>
                    <td>{{ $slot->duration ?? '-' }} min</td>
                    <td>₹{{ $slot->price ?? 0 }}</td>
                    <!-- Status -->
                    <td>
                        @if ($slot->status == 'pending')
                            <span class="badge bg-warning">Pending</span>
                        @elseif($slot->status == 'accepted')
                            <span class="badge bg-success">Accepted</span>
                        @elseif($slot->status == 'cancelled')
                            <span class="badge bg-danger">Cancelled</span>
                        @elseif($slot->status == 'confirmed')
                            <span class="badge bg-primary">Confirmed</span>
                        @elseif($slot->status == 'notified')
                            <span class="badge bg-info">Notified</span>
                        @elseif($slot->status == 'ongoing')
                            <span class="badge bg-secondary">Ongoing</span>
                        @elseif($slot->status == 'completed')
                            <span class="badge bg-success bg-opacity-75">Completed</span>
                        @else
                            <span class="badge bg-light text-dark">{{ ucfirst($slot->status) }}</span>
                        @endif
                    </td>
                    <!-- Payment -->
                    <td>
                        @if ($slot->payment_status == 'paid')
                            <span class="badge bg-success">Paid</span>
                        @else
                            <span class="badge bg-warning">Pending</span>
                        @endif
                    </td>
                    <!-- OTP -->
                    <td>{{ $slot->otp_code ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">No Booking Slots Found</td>
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
