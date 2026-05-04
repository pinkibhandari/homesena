<table class="table table-hover table-sm align-middle mb-0">

    <thead class="table-light sticky-top">
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Resolved At</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody id="resolvedTable">

        @forelse($resolvedSupports as $key => $item)
            <tr>

                <td>{{ $resolvedSupports->firstItem() + $key }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->phone }}</td>

                <td><span class="badge bg-success">Resolved</span></td>

                <td>{{ $item->updated_at }}</td>

                <td>
                    <a href="{{ route('admin.user_supports.show', $item->id) }}"
                        class="btn btn-sm btn-primary">Details</a>
                </td>

            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">No Resolved Tickets</td>
            </tr>
        @endforelse

    </tbody>
</table>

<!-- Pagination -->
<div class="row align-items-center mt-2 px-1">
    <div class="col-md-6">
        <small class="text-muted">
            Showing {{ $resolvedSupports->firstItem() ?? 0 }} to {{ $resolvedSupports->lastItem() ?? 0 }} of {{ $resolvedSupports->total() }} results
        </small>
    </div>
    <div class="col-md-6 d-flex justify-content-end">
        {{ $resolvedSupports->appends(request()->except('ajax'))->links('pagination::bootstrap-5') }}
    </div>
</div>
