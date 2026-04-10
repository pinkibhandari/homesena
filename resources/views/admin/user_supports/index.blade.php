@extends('admin.layouts.master')

@section('title', 'User Support')

@section('content')

<div class="card shadow-sm">

    <!-- ALERT -->
    <div class="p-3">
        @include('admin.layouts.partials.alerts')
    </div>

    <!-- HEADER -->
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold">User Support</h5>
    </div>

    <hr class="my-0">

    <!-- 🔝 STATS CARDS -->
    <div class="row px-3 py-4">

        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <h6 class="text-muted mb-1">Total Tickets</h6>
                <h3 class="fw-bold mb-0" id="totalCount">{{ $totalTickets }}</h3>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <h6 class="text-warning mb-1">Pending</h6>
                <h3 class="fw-bold text-warning mb-0" id="pendingCount">{{ $pendingTickets }}</h3>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <h6 class="text-success mb-1">Resolved</h6>
                <h3 class="fw-bold text-success mb-0" id="resolvedCount">{{ $resolvedTickets }}</h3>
            </div>
        </div>

    </div>

    <hr class="my-0">

    <!-- 🔽 ACCORDION -->
    <div class="accordion px-3 pb-3" id="supportAccordion">

        <!-- 🔸 Pending -->
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button fw-semibold"
                    data-bs-toggle="collapse"
                    data-bs-target="#pendingSection">

                    Pending Tickets
                    <span class="badge bg-label-warning rounded-pill ms-2" id="pendingBadge">
                        {{ count($pendingSupports) }}
                    </span>
                </button>
            </h2>

            <!-- ✅ FIXED -->
            <div id="pendingSection"
                class="accordion-collapse collapse show"
                data-bs-parent="#supportAccordion">

                <div class="accordion-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody id="pendingTable">
                                @forelse($pendingSupports as $key => $item)
                                    <tr id="row-{{ $item->id }}">
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->phone }}</td>

                                        <td>
                                            <button type="button"
                                                class="btn btn-sm btn-warning resolveBtn d-flex align-items-center gap-1"
                                                data-id="{{ $item->id }}">
                                                <i class="ri-time-line"></i> Pending
                                            </button>
                                        </td>

                                        <td>{{ $item->created_at }}</td>

                                        <td>
                                            <a href="{{ route('admin.user_supports.show', $item->id) }}"
                                                class="btn btn-sm btn-primary">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No Pending Tickets</td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>

                </div>
            </div>
        </div>

        <!-- 🔸 Resolved -->
        <div class="accordion-item mt-2">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed fw-semibold"
                    data-bs-toggle="collapse"
                    data-bs-target="#resolvedSection">

                    Resolved Tickets
                    <span class="badge bg-label-success rounded-pill ms-2" id="resolvedBadge">
                        {{ count($resolvedSupports) }}
                    </span>
                </button>
            </h2>

            <!-- ✅ FIXED -->
            <div id="resolvedSection"
                class="accordion-collapse collapse"
                data-bs-parent="#supportAccordion">

                <div class="accordion-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle">
                            <thead class="table-light">
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
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->phone }}</td>

                                        <td>
                                            <span class="badge bg-label-success rounded-pill">
                                                Resolved
                                            </span>
                                        </td>

                                        <td>{{ $item->updated_at }}</td>

                                        <td>
                                            <a href="{{ route('admin.user_supports.show', $item->id) }}"
                                                class="btn btn-sm btn-secondary">
                                                Details
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No Resolved Tickets</td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>

</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).on('click', '.resolveBtn', function () {

    let id = $(this).data('id');

    Swal.fire({
        title: 'Are you sure?',
        text: "Mark this ticket as resolved?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: "/admin/user_supports/" + id,
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    _method: "PUT"
                },

                success: function (res) {

                    if (!res.success) {
                        Swal.fire('Error', res.message, 'error');
                        return;
                    }

                    Swal.fire('Resolved!', res.message, 'success');

                    let data = res.data;

                    $('#row-' + data.id).remove();

                    let row = `
                        <tr>
                            <td>--</td>
                            <td>${data.name}</td>
                            <td>${data.email}</td>
                            <td>${data.phone}</td>
                            <td>
                                <span class="badge bg-label-success rounded-pill">Resolved</span>
                            </td>
                            <td>${data.updated_at}</td>
                            <td>
                                <a href="${data.view_url}" class="btn btn-sm btn-secondary">View</a>
                            </td>
                        </tr>
                    `;

                    $('#resolvedTable').prepend(row);

                    let pending = parseInt($('#pendingCount').text()) || 0;
                    let resolved = parseInt($('#resolvedCount').text()) || 0;

                    $('#pendingCount').text(pending - 1);
                    $('#resolvedCount').text(resolved + 1);

                    $('#pendingBadge').text(pending - 1);
                    $('#resolvedBadge').text(resolved + 1);
                }
            });
        }
    });
});
</script>

@endsection