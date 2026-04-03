@extends('admin.layouts.master')
@section('title', 'Expert Table')
@section('content')
    <div class="card">
        <!-- ALERT MESSAGE -->
        <div class="p-3">
            @include('admin.layouts.partials.alerts')
        </div>
        <!-- Header -->
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Expert</h5>
            <div class="d-flex align-items-center gap-3">
                <!-- Search -->
                <form method="GET" action="{{ route('admin.experts.index') }}" class="d-flex align-items-center">
                    <div class="d-flex align-items-center">
                        <span class="me-2">Search:</span>
                        <input name="search" type="search" class="form-control form-control-sm"
                            placeholder="Search experts..." value="{{ request('search') }}" style="width:200px;">
                    </div>
                </form>
                <!-- Add Button -->
                <a href="{{ route('admin.experts.create') }}" class="btn btn-primary btn-sm">
                    <i class="ri-add-line me-1"></i> Add
                </a>
            </div>
        </div>
        <hr class="my-0">
        <!-- Show Entries -->
        <div class="row px-4 py-3 align-items-center">
        </div>
        <!-- Table -->
        <div class="table-responsive px-4 pb-3">
            <table class="table table-hover align-middle table-bordered">
                <thead class="bg-label-secondary">
                    <tr>
                        <th width="60">ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Registration Code</th>
                        <th>Online</th>
                        <th>Approval Status</th>
                        <th>Approval Actions</th>
                        <th width="120">Status</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($experts as $expert)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <span class="fw-semibold">{{ $expert->name ?? ' ' }}</span>
                            </td>
                            <td>{{ $expert->phone }}</td>
                            <td class="reg-td">{{ $expert->expertDetail?->registration_code }}</td>
                            <!-- <td>{{ $expert->expertDetail?->onboarding_agent_code }}</td> -->
                            <td>
                                <div class="form-check form-switch">
                                  <input class="form-check-input" type="checkbox"style="transform: scale(1.3); cursor: not-allowed;" disabled {{ $expert->expertDetail?->is_online ? 'checked' : '' }}>
                                </div>
                            </td>

                            <td class="status-badge">
                                @if ($expert->expertDetail?->approval_status === 'pending')
                                    <span class="badge rounded-pill bg-label-secondary">Pending</span>
                                @elseif($expert->expertDetail?->approval_status === 'approved')
                                    <span class="badge rounded-pill bg-label-primary">Approved</span>
                                @endif
                            </td>
                            <td>
                                @if ($expert->expertDetail?->approval_status === 'pending')
                                    <div class="d-flex align-items-center">
                                        <div class="form-check form-switch m-0">
                                            <input class="form-check-input statusToggle" type="checkbox" name="approval_status"
                                                value="1" data-id="{{ $expert->id }}">
                                        </div>
                                    </div>
                                @else
                                    <button class="btn btn-primary btn-sm" disabled>
                                        ✔ Approved
                                    </button>
                                @endif
                            </td>
                            {{-- <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox"
                                        style="transform: scale(1.3); cursor: not-allowed;" disabled {{ $expert->status === 1 ?
                                    'checked' : '' }}>
                                </div>
                            </td> --}}
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input toggle-status" type="checkbox" data-id="{{ $expert->id }}"
                                        style="transform: scale(1.3); cursor:pointer;" {{ $expert->status == 1 ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                                        data-bs-toggle="dropdown">
                                        <i class="ri-more-2-line"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.experts.edit', $expert->id) }}">
                                                <i class="ri-pencil-line me-2"></i>
                                                Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.experts.show', $expert->id) }}">
                                                <i class="ri-eye-line me-2"></i>
                                                Details
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.experts.destroy', $expert->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="dropdown-item text-danger"
                                                    onclick="return confirm('Are you sure you want to delete this expert?')"
                                                    type="submit">
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
                            <td colspan="6" class="text-center">No users found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination (Dynamic) -->
        <div class="row px-4 pb-3 align-items-center">
            {{ $experts->links('pagination::bootstrap-5') }}
        </div>

@endsection
    @push('scripts')
        <script>
            $(document).ready(function () {

                $(document).on('change', '.statusToggle', function () {

                    let toggle = $(this);
                    let status = toggle.is(':checked') ? 1 : 0;
                    let id = toggle.data('id');
                    let row = toggle.closest('tr');

                    if (status === 1) {
                        if (!confirm("Are you sure you want to approve this expert?")) {
                            toggle.prop('checked', false);
                            return;
                        }
                    }

                    $.ajax({
                        url: '/admin/update-approve-status', // ✅ FIXED
                        type: 'POST',
                        data: {
                            id: id,
                            approval_status: status,
                            _token: '{{ csrf_token() }}'
                        },

                        success: function (response) {

                            if (response.status) {

                                // Approved UI
                                toggle.closest('.align-items-center').html(`
                            <button class="btn btn-primary btn-sm" disabled>
                                ✔ Approved
                            </button>
                        `);

                                row.find('.status-badge').html(`
                            <span class="badge rounded-pill bg-label-primary">
                                Approved
                            </span>
                        `);

                                if (response.data && response.data.registration_code) {
                                    row.find('.reg-td').text(response.data.registration_code);
                                }

                            } else {
                                alert('Update failed');
                                toggle.prop('checked', !status);
                            }
                        },

                        error: function () {
                            alert('Something went wrong');
                            toggle.prop('checked', !status);
                        }
                    });

                });

            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                document.querySelectorAll('.toggle-status').forEach(function (toggle) {

                    toggle.addEventListener('change', function () {

                        let id = this.dataset.id;
                        let value = this.checked ? 1 : 0;
                        let checkbox = this;

                        fetch(`/admin/experts/${id}`, {
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
                            .then(response => response.json())
                            .then(data => {

                                if (!data.status) {
                                    alert('Update failed');
                                    checkbox.checked = !value;
                                }

                            })
                            .catch(() => {
                                alert('Something went wrong');
                                checkbox.checked = !value;
                            });

                    });

                });

            });
        </script>
    @endpush