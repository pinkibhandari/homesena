@extends('admin.layouts.master')

@section('title', 'Push Notifications')

@section('content')

    <div class="card">

        <!-- ALERT MESSAGE -->
        <div class="p-3">
            @include('admin.layouts.partials.alerts')
        </div>

        <!-- Header -->
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">

            <h5 class="card-title mb-0">
                Push Notifications
            </h5>

            <div class="d-flex align-items-center gap-3 flex-wrap">

                <!-- Search Form -->
                <form method="GET" action="{{ route('admin.push_notifications.index') }}"
                    class="d-flex align-items-center gap-2 flex-wrap">

                    <!-- Search -->
                    <div class="d-flex align-items-center">
                        <span class="me-2 small fw-semibold">Search:</span>

                        <input type="search" name="search" class="form-control form-control-sm" placeholder="Search..."
                            value="{{ request('search') }}" style="width:180px;">
                    </div>

                    <!-- Status -->
                    <select name="status" class="form-select form-select-sm" style="width:120px;">

                        <option value="">Status</option>

                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>

                    <!-- Send Type -->
                    <select name="send_type" class="form-select form-select-sm" style="width:140px;">

                        <option value="">Send Type</option>

                        <option value="all" {{ request('send_type') == 'all' ? 'selected' : '' }}>
                            All Users
                        </option>

                        <option value="location" {{ request('send_type') == 'location' ? 'selected' : '' }}>
                            Location Wise
                        </option>

                    </select>

                    <!-- User Type -->
                    <select name="user_type" class="form-select form-select-sm" style="width:130px;">

                        <option value="">User Type</option>

                        <option value="user" {{ request('user_type') == 'user' ? 'selected' : '' }}>
                            User
                        </option>

                        <option value="expert" {{ request('user_type') == 'expert' ? 'selected' : '' }}>
                            Expert
                        </option>

                    </select>

                    <!-- Search Button -->
                    <button class="btn btn-primary btn-sm">
                        <i class="ri-search-line"></i>
                    </button>

                    <!-- Reset -->
                    <a href="{{ route('admin.push_notifications.index') }}" class="btn btn-outline-secondary btn-sm">

                        <i class="ri-refresh-line"></i>
                    </a>

                </form>
                <!-- Add Button -->
                <a href="{{ route('admin.push_notifications.create') }}" class="btn btn-primary btn-sm">

                    <i class="ri-add-line me-1"></i>
                    Add
                </a>
            </div>
        </div>
        <hr class="my-0 mb-2">
        <!-- Table -->
        <div class="table-responsive px-4 pb-3">

            <table class="table table-hover align-middle table-bordered">

                <thead class="bg-label-secondary">
                    <tr>
                        <th width="60">#</th>
                        <th>Title</th>
                        <th>Message</th>
                        <th>Send Type</th>
                        <th>User Type</th>
                        <th width="120">Status</th>
                        <th>Created At</th>
                        <th width="120" class="text-center">Action</th>

                    </tr>
                </thead>

                <tbody>

                    @forelse($notifications as $key => $notification)
                        <tr>

                            <!-- Pagination Index -->
                            <td>
                                {{ $notifications->firstItem() + $key }}
                            </td>

                            <!-- Title -->
                            <td>
                                {{ Str::limit($notification->title, 50) }}
                            </td>

                            <!-- Message -->
                            <td style="max-width:250px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                {{ Str::limit(strip_tags($notification->message), 50) }}
                            </td>

                            <!-- Send Type -->
                            <td>
                                {{ ucfirst($notification->send_type) }}
                            </td>

                            <!-- User Type -->
                            <td>
                                {{ ucfirst($notification->user_type) }}
                            </td>

                            <!-- Status Toggle -->
                            <td>
                                <div class="form-check form-switch">

                                    <input type="checkbox" class="form-check-input toggle-status"
                                        data-id="{{ $notification->id }}" style="transform: scale(1.3); cursor:pointer;"
                                        {{ $notification->status ? 'checked' : '' }}>

                                </div>
                            </td>

                            <!-- Created -->
                            <td>
                                {{ $notification->created_at->format('d M Y h:i A') }}
                            </td>
                            <td class="text-center">
                                <div class="dropdown">

                                    <button type="button"
                                        class="btn btn-sm btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">

                                        <i class="ri-more-2-line"></i>
                                    </button>

                                    <div class="dropdown-menu dropdown-menu-end">

                                        <!-- Show -->
                                        <a class="dropdown-item"
                                            href="{{ route('admin.push_notifications.show', $notification->id) }}">

                                            <i class="ri-eye-line me-2"></i>
                                            View
                                        </a>

                                        <!-- Edit -->
                                        <a class="dropdown-item"
                                            href="{{ route('admin.push_notifications.edit', $notification->id) }}">

                                            <i class="ri-edit-box-line me-2"></i>
                                            Edit
                                        </a>

                                        <!-- Delete -->
                                        <form action="{{ route('admin.push_notifications.destroy', $notification->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this notification?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="ri-delete-bin-line me-2"></i>
                                                Delete
                                            </button>

                                        </form>

                                    </div>

                                </div>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="text-center">
                                No Notifications Found
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        <!-- Pagination -->
        <div class="row px-4 pb-3 align-items-center">

            {{ $notifications->links('pagination::bootstrap-5') }}

        </div>

    </div>

    <!-- Status Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            document.querySelectorAll('.toggle-status').forEach(function(toggle) {

                toggle.addEventListener('change', function() {

                    let id = this.dataset.id;
                    let value = this.checked ? 1 : 0;

                    // Confirm
                    let confirmAction = confirm(
                        value === 1 ?
                        "Are you sure you want to activate this?" :
                        "Are you sure you want to inactivate this?"
                    );

                    if (!confirmAction) {
                        this.checked = !this.checked;
                        return;
                    }

                    fetch(`/admin/push_notifications/${id}`, {

                            method: 'POST',

                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },

                            body: JSON.stringify({
                                _method: 'PUT',
                                status: value
                            })

                        })
                        .then(res => res.json())
                        .then(data => {

                            if (!data.status) {

                                alert('Update failed');
                                this.checked = !value;
                            }

                        })
                        .catch(() => {

                            alert('Something went wrong');
                            this.checked = !value;

                        });

                });

            });

        });
    </script>

@endsection
