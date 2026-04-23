<div class="table-responsive">
    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>#</th>
                <th>Device</th>
                <th>Device Type</th>
                <th>FCM Token</th>
                <th>Token ID</th>
                <th>Status</th>
                <th>Last Used</th>
            </tr>
        </thead>
        <tbody>
            @forelse($devices as $device)
                <tr>
                    <td>{{ $devices->firstItem() + $loop->index }}</td>
                    <td>{{ $device->device_id ?? '-' }}</td>
                    <td>
                        <span class="badge bg-label-info rounded-pill">
                            {{ ucfirst($device->device_type) ?? 'N/A' }}
                        </span>
                    </td>
                    <td>{{ $device->platform ?? '-' }}</td>
                    <td style="max-width: 200px; word-break: break-all;">
                        {{ $device->token_id ?? '-' }}
                    </td>
                    <td>
                        @if ($device->is_active ?? 1)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td>{{ $device->updated_at ?? '-' }}</td>
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
