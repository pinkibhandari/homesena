<div class="table-responsive">
    <table class="table table-bordered table-sm">
        <thead class="bg-label-secondary">
            <tr>
                <th>#</th>
                <th>User ID</th>
                <th>Token ID</th>
                <th>Device ID</th>
                <th>Device Type</th>
                <th>FCM Token</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($devices as $device)
                <tr>
                    <td>{{ $devices->firstItem() + $loop->index }}</td>
                    <td>{{ $device->user_id }}</td>
                    <td>{{ $device->token_id ?? 'N/A' }}</td>
                    <td>{{ $device->device_id ?? 'N/A' }}</td>
                    <td>
                        <span class="badge bg-label-info rounded-pill">
                            {{ ucfirst($device->device_type) ?? 'N/A' }}
                        </span>
                    </td>
                    <td style="max-width:200px; overflow:hidden; text-overflow:ellipsis;">
                        {{ $device->fcm_token ?? 'N/A' }}
                    </td>
                    <td>{{ $device->updated_at }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No Devices Found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<!-- Pagination -->
<div class="row align-items-center mt-2 px-1">
    <div class="col-md-6">
        <small class="text-muted">
            Showing {{ $devices->firstItem() ?? 0 }} to {{ $devices->lastItem() ?? 0 }} of {{ $devices->total() }} results
        </small>
    </div>
    <div class="col-md-6 d-flex justify-content-end">
        {{ $devices->links('pagination::bootstrap-5') }}
    </div>
</div>
