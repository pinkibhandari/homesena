@extends('admin.layouts.master')

@section('title', 'Training Center Table')

@section('content')

    <div class="card">

        <!-- ALERT -->
        <div class="p-3">
            @include('admin.layouts.partials.alerts')
        </div>

        <!-- Header -->
        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="card-title mb-0"> Training Center Table</h5>

            <div class="d-flex align-items-center gap-3">

                <!-- Search -->
                <form method="GET" action="{{ route('admin.training_centers.index') }}" class="d-flex align-items-center">
                    <span class="me-2">Search:</span>
                    <input type="search" name="search" value="{{ request('search') }}" class="form-control form-control-sm"
                        placeholder="Search centers..." style="width:200px;">
                </form>

                <!-- Add -->
                <a href="{{ route('admin.training_centers.create') }}" class="btn btn-primary btn-sm">
                    <i class="ri-add-line me-1"></i> Add Training Center
                </a>

            </div>

        </div>

        <hr class="my-0">

        <!-- Table -->
        <div class="table-responsive px-4 pb-3">

            <table class="table table-hover align-middle table-bordered">

                <thead class="bg-label-secondary">
                    <tr>
                        <th width="60">ID</th>
                        <th>Name</th>
                        <th>City</th>
                        <th>Address</th>
                        <th width="120">Status</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($centers as $center)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                <span class="fw-semibold">{{ $center->name }}</span>
                            </td>

                            <td>{{ $center->city }}</td>

                            <td>{{ $center->address }}</td>

                            <td>
                                @if ($center->status == 1)
                                    <span class="badge rounded-pill bg-label-success">ACTIVE</span>
                                @else
                                    <span class="badge rounded-pill bg-label-danger">INACTIVE</span>
                                @endif
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
                                                href="{{ route('admin.training_centers.edit', $center->id) }}">
                                                <i class="ri-pencil-line me-2"></i> Edit
                                            </a>
                                        </li>

                                        <li>
                                            <form method="POST"
                                                action="{{ route('admin.training_centers.destroy', $center->id) }}">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="dropdown-item text-danger"
                                                    onclick="return confirm('Delete center?')">
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
                            <td colspan="6" class="text-center">No training centers found</td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        <!-- Pagination -->
        <div class="px-4 pb-3">
            {{ $centers->links('pagination::bootstrap-5') }}
        </div>

    <!-- </div> -->

@endsection
