<div class="table-responsive">
    <table class="table table-bordered table-sm">
        <thead class="bg-label-secondary">
            <tr>
                <th>#</th>
                <th>Flat No</th>
                <th>Address</th>
                <th>Landmark</th>
                <th>Save As</th>
                <th>Pets</th>
                <th>Lat</th>
                <th>Long</th>
                <th>Updated</th>
            </tr>
        </thead>
        <tbody>
            @forelse($addresses as $address)
                <tr>
                    <td>{{ $addresses->firstItem() + $loop->index }}</td>
                    <td>{{ $address->flat_no ?? '-' }}</td>
                    <td>{{ $address->address ?? '-' }}</td>
                    <td>{{ $address->landmark ?? '-' }}</td>
                    <td>{{ ucfirst($address->save_as) }}</td>
                    <td>{{ $address->pets ?? '-' }}</td>
                    <td>{{ $address->address_lat ?? '-' }}</td>
                    <td>{{ $address->address_long ?? '-' }}</td>
                    <td>{{ $address->updated_at ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">No Addresses Found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<!-- Pagination -->
<div class="row align-items-center mt-2 px-1">
    <div class="col-md-6">
        <small class="text-muted">
            Showing {{ $addresses->firstItem() ?? 0 }} to {{ $addresses->lastItem() ?? 0 }} of {{ $addresses->total() }} results
        </small>
    </div>
    <div class="col-md-6 d-flex justify-content-end">
        {{ $addresses->links('pagination::bootstrap-5') }}
    </div>
</div>
