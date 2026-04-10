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

        <!-- 🔝 STATS -->
        <div class="row px-3 py-3 g-3">

            <div class="col-md-4">
                <div
                    class="d-flex justify-content-between bg-white shadow-sm rounded-3 px-3 py-2 border-start border-4 border-primary">
                    <div>
                        <h6 class="mb-0 text-muted small">Total Tickets</h6>
                        <small class="text-muted">All requests</small>
                    </div>
                    <h4 class="fw-bold text-primary" id="totalCount">{{ $totalTickets }}</h4>
                </div>
            </div>

            <div class="col-md-4">
                <div
                    class="d-flex justify-content-between bg-white shadow-sm rounded-3 px-3 py-2 border-start border-4 border-warning">
                    <div>
                        <h6 class="mb-0 text-muted small">Pending</h6>
                        <small class="text-muted">Waiting response</small>
                    </div>
                    <h4 class="fw-bold text-warning" id="pendingCount">{{ $pendingTickets }}</h4>
                </div>
            </div>

            <div class="col-md-4">
                <div
                    class="d-flex justify-content-between bg-white shadow-sm rounded-3 px-3 py-2 border-start border-4 border-success">
                    <div>
                        <h6 class="mb-0 text-muted small">Resolved</h6>
                        <small class="text-muted">Completed</small>
                    </div>
                    <h4 class="fw-bold text-success" id="resolvedCount">{{ $resolvedTickets }}</h4>
                </div>
            </div>

        </div>

        <hr class="my-0">

        <!-- 🔍 SEARCH + FILTER -->
        <div class="px-3 pt-3">
            <form method="GET" class="row g-2">

                <div class="col-md-5">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control form-control-sm" placeholder="Search name, email, phone...">
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary btn-sm w-100">Filter</button>
                </div>

                <div class="col-md-2">
                    <a href="{{ url()->current() }}" class="btn btn-light btn-sm w-100">Reset</a>
                </div>

            </form>
        </div>

        <!-- 🔽 TABS -->
        <div class="px-3 py-3">

            <ul class="nav nav-tabs mb-3">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pendingTab">
                        Pending
                        <span
                            class="badge bg-warning text-dark ms-1">{{ $pendingSupports->total() ?? count($pendingSupports) }}</span>
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#resolvedTab">
                        Resolved
                        <span
                            class="badge bg-success ms-1">{{ $resolvedSupports->total() ?? count($resolvedSupports) }}</span>
                    </button>
                </li>
            </ul>

            <div class="tab-content">

                <!-- 🔴 PENDING -->
                <div class="tab-pane fade show active" id="pendingTab">

                    <div class="table-responsive" style="max-height:70vh; overflow:auto;">

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

                        <div class="mt-2">
                            {{ $pendingSupports->appends(request()->query())->links() }}
                        </div>

                    </div>
                </div>

                <!-- 🟢 RESOLVED -->
                <div class="tab-pane fade" id="resolvedTab">

                    <div class="table-responsive" style="max-height:70vh; overflow:auto;">

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

                        <div class="mt-2">
                            {{ $resolvedSupports->appends(request()->query())->links() }}
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
        $(document).on('click', '.resolveBtn', function() {

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

                        success: function(res) {

                            Swal.fire('Success', res.message, 'success');

                            $('#row-' + res.data.id).remove();

                            location.reload(); // safe update for counts + pagination
                        }
                    });
                }
            });
        });
    </script>

@endsection
