@extends('admin.layouts.master')

@section('title', 'Service Dashboard')

@section('content')

<div class="card">

    <!-- Header -->
    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="card-title mb-0">Services List</h5>

        <div class="d-flex align-items-center gap-3">

            <!-- Search -->
            <div class="d-flex align-items-center">
                <span class="me-2">Search:</span>
                <input type="search"
                       class="form-control form-control-sm"
                       placeholder="Search services..."
                       style="width:200px;">
            </div>

            <!-- Add Button -->
            <a href="{{ route('admin.services.create') }}" class="btn btn-primary btn-sm">
                <i class="ri-add-line me-1"></i> Add Service
            </a>

        </div>

    </div>

    <hr class="my-0">

    <!-- Show Entries -->
    <div class="row px-4 py-3 align-items-center">

        <div class="col-md-6">

            <div class="d-flex align-items-center gap-2">

                <span>Show</span>

                <select class="form-select form-select-sm" style="width:80px;">
                    <option>7</option>
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                </select>

                <span>entries</span>

            </div>

        </div>

    </div>


    <!-- Table -->
    <div class="table-responsive px-4 pb-3">

        <table class="table table-hover align-middle table-bordered">

            <thead class="bg-label-secondary">

                <tr>
                    <th width="60">ID</th>
                    <th>Service</th>
                    <th>Description</th>
                    <th width="120">Image</th>
                    <th width="120">Status</th>
                    <th width="120">Actions</th>
                </tr>

            </thead>

            <tbody>

                <tr>

                    <td>1</td>

                    <td>
                        <span class="fw-semibold">Home Cleaning</span>
                    </td>

                    <td style="max-width:350px;">
                        Professional home cleaning service for kitchen,
                        bathroom and living areas.
                    </td>

                    <td>
                        <img src="https://via.placeholder.com/80x50"
                             class="rounded"
                             style="object-fit:cover;">
                    </td>

                    <td>
                        <span class="badge rounded-pill bg-label-success">
                            ACTIVE
                        </span>
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
                                       href="{{ route('admin.services.edit') }}">
                                        <i class="ri-pencil-line me-2"></i>
                                        Edit
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item text-danger" href="#">
                                        <i class="ri-delete-bin-6-line me-2"></i>
                                        Delete
                                    </a>
                                </li>

                            </ul>

                        </div>

                    </td>

                </tr>

            </tbody>

        </table>

    </div>


    <!-- Pagination -->
    <div class="row px-4 pb-3 align-items-center">

        <div class="col-md-6">

            <small class="text-muted">
                Showing 1 to 7 of 10 entries
            </small>

        </div>

        <div class="col-md-6 text-end">

            <nav>

                <ul class="pagination pagination-sm justify-content-end mb-0">

                    <li class="page-item disabled">
                        <a class="page-link">
                            <i class="ri-arrow-left-s-line"></i>
                        </a>
                    </li>

                    <li class="page-item active">
                        <a class="page-link">1</a>
                    </li>

                    <li class="page-item">
                        <a class="page-link">2</a>
                    </li>

                    <li class="page-item">
                        <a class="page-link">
                            <i class="ri-arrow-right-s-line"></i>
                        </a>
                    </li>

                </ul>

            </nav>

        </div>

    </div>

</div>

@endsection