<table class="table table-hover table-sm align-middle mb-0">

    <thead class="table-light sticky-top">
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody id="pendingTable">

        @forelse($pendingSupports as $key => $item)
            <tr id="row-{{ $item->id }}">

                <td>{{ $pendingSupports->firstItem() + $key }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->phone }}</td>

                <td>
                    <button class="btn btn-warning btn-sm resolveBtn"
                        data-id="{{ $item->id }}">
                        Pending
                    </button>
                </td>

                <td>{{ $item->created_at }}</td>

                <td>
                    <a href="{{ route('admin.user_supports.show', $item->id) }}"
                        class="btn btn-sm btn-primary">Details</a>
                </td>

            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">No Pending Tickets</td>
            </tr>
        @endforelse

    </tbody>
</table>

<!-- Pagination -->
<div class="row align-items-center mt-2 px-1">
    <div class="col-md-6">
        <small class="text-muted">
            Showing {{ $pendingSupports->firstItem() ?? 0 }} to {{ $pendingSupports->lastItem() ?? 0 }} of {{ $pendingSupports->total() }} results
        </small>
    </div>
    <div class="col-md-6 d-flex justify-content-end">
        {{ $pendingSupports->appends(request()->except('ajax'))->links('pagination::bootstrap-5') }}
    </div>
</div>
