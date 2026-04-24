<div class="table-responsive">
    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>#</th>
                <th>Expert</th>
                <th>Online At</th>
                <th>Offline At</th>
                <th>Duration</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ $logs->firstItem() + $loop->index }}</td>
                    <td>{{ $expert->name }}</td>
                    <td>{{ $log->online_at ?? '-' }}</td>
                    <td>{{ $log->offline_at ?? '-' }}</td>
                    <td>
                        @if ($log->online_at && $log->offline_at)
                            {{ \Carbon\Carbon::parse($log->online_at)->diffForHumans($log->offline_at, true) }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $log->updated_at ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No Logs Found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<!-- Pagination -->
<div class="row align-items-center mt-2 px-1">
    <div class="col-md-6">
        <small class="text-muted">
            Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} results
        </small>
    </div>
    <div class="col-md-6 d-flex justify-content-end">
        {{ $logs->links('pagination::bootstrap-5') }}
    </div>
</div>
