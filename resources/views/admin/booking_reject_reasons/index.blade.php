@extends('admin.layouts.master')

@section('title', 'Booking Reject Reasons')

@section('content')

    <div class="card">

        <!-- ALERT MESSAGE -->
        <div class="p-3">
            @include('admin.layouts.partials.alerts')
        </div>

        <!-- Header -->
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">

            <h5 class="card-title mb-0">Booking Reject Reasons</h5>

            <form method="GET" action="{{ route('admin.booking_reject_reasons.index') }}"
                class="d-flex align-items-center gap-2 flex-wrap">

                <!-- SEARCH -->
                <input name="search" type="search" class="form-control form-control-sm" placeholder="Search..."
                    value="{{ request('search') }}" style="width:180px;">

                <!-- STATUS -->
                <select name="status" class="form-select form-select-sm" style="width:120px;">
                    <option value="">Status</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                </select>

                <!-- SEARCH BUTTON -->
                <button class="btn btn-primary btn-sm" data-bs-toggle="tooltip" title="Search">
                    <i class="ri-search-line"></i>
                </button>

                <!-- RESET -->
                <a href="{{ route('admin.booking_reject_reasons.index') }}" class="btn btn-outline-secondary btn-sm"
                    data-bs-toggle="tooltip" title="Reset Filters">
                    <i class="ri-refresh-line"></i>
                </a>

            </form>

            <!-- ADD BUTTON -->
            <a href="{{ route('admin.booking_reject_reasons.create') }}" class="btn btn-primary btn-sm">
                <i class="ri-add-line me-1"></i> Add
            </a>

        </div>

        <hr class="my-0">

        <!-- Table -->
        <div class="table-responsive px-4 pb-3">

            <table class="table table-hover align-middle table-bordered">

                <thead class="bg-label-secondary">
                    <tr>
                        <th width="60">ID</th>
                        <th>Title</th>
                        <th width="120">Status</th>
                        <th width="200">Created At</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($reasons as $reason)
                        <tr>

                            <td>{{ $reasons->firstItem() + $loop->index }}</td>

                            <td>
                                <span class="fw-semibold">{{ $reason->title }}</span>
                            </td>

                            <!-- STATUS TOGGLE -->
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input toggle-status" type="checkbox"
                                        data-id="{{ $reason->id }}" style="transform: scale(1.3); cursor:pointer;"
                                        {{ $reason->status == 1 ? 'checked' : '' }}>
                                </div>
                            </td>

                            <td>
                                {{ $reason->created_at->format('d M Y h:i A') }}
                            </td>

                            <td>
                                <div class="dropdown">

                                    <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                                        data-bs-toggle="dropdown">
                                        <i class="ri-more-2-line"></i>
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end">

                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('admin.booking_reject_reasons.edit', $reason->id) }}">
                                                <i class="ri-pencil-line me-2"></i> Edit
                                            </a>
                                        </li>

                                        <li>
                                            <form method="POST"
                                                action="{{ route('admin.booking_reject_reasons.destroy', $reason->id) }}">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="dropdown-item text-danger"
                                                    onclick="return confirm('Delete this reason?')">
                                                    <i class="ri-delete-bin-6-line me-2"></i> Delete
                                                </button>
                                            </form>
                                        </li>

                                    </ul>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No booking reject reasons found</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>

        <!-- Pagination -->
        <div class="row px-4 pb-3">
            {{ $reasons->links('pagination::bootstrap-5') }}
        </div>

    </div>

    <!-- AJAX STATUS TOGGLE SCRIPT -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            document.querySelectorAll('.toggle-status').forEach(function(toggle) {

                toggle.addEventListener('change', function() {

                    let id = this.dataset.id;
                    let value = this.checked ? 1 : 0;
                    let checkbox = this;

                    let confirmAction = confirm(
                        value === 1 ?
                        "Activate this reason?" :
                        "Deactivate this reason?"
                    );

                    if (!confirmAction) {
                        checkbox.checked = !checkbox.checked;
                        return;
                    }

                    fetch(`/admin/booking_reject_reasons/${id}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                _method: 'PUT',
                                status: value
                            })
                        })
                        .then(async (res) => {

                            //  SAFE JSON parsing (important fix)
                            let data;
                            try {
                                data = await res.json();
                            } catch (e) {
                                throw new Error("Invalid JSON response");
                            }

                            if (!res.ok || !data.status) {
                                throw new Error(data.message || 'Update failed');
                            }

                            return data;
                        })
                        .then(() => {
                            //  success (optional UI update)
                            console.log('Status updated successfully');
                        })
                        .catch((error) => {

                            alert(error.message || 'Something went wrong');

                            //  revert checkbox state
                            checkbox.checked = !checkbox.checked;
                        });

                });

            });

        });
    </script>

@endsection
