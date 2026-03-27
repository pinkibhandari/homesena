@extends('admin.layouts.master')

@section('title', 'Service Dashboard')

@section('content')

    <div class="card">

        <!-- ALERT -->
        <div class="p-3">
            @include('admin.layouts.partials.alerts')
        </div>

        <!-- Header -->
        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="card-title mb-0">Services List</h5>

            <div class="d-flex align-items-center gap-3">

                <!-- Search -->
                <form method="GET" action="{{ route('admin.services.index') }}" class="d-flex align-items-center">
                    <span class="me-2">Search:</span>
                    <input type="search" name="search" value="{{ request('search') }}" class="form-control form-control-sm"
                        placeholder="Search services..." style="width:200px;">
                </form>

                <!-- Add Button -->
                <a href="{{ route('admin.services.create') }}" class="btn btn-primary btn-sm">
                    <i class="ri-add-line me-1"></i> Add Service
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
                        <th>Service</th>
                        <th>Description</th>
                        <th width="120">Image</th>
                        <th width="120">Status</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($services as $service)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                <span class="fw-semibold">{{ $service->name }}</span>
                            </td>

                            <td style="max-width:350px;">
                                {!! Str::words(strip_tags($service->description), 10, '...') !!}
                            </td>

                            <td>
                                @if ($service->image)
                                    <img src="{{ asset('storage/' . $service->image) }}" class="rounded"
                                        style="width:80px; height:50px; object-fit:cover;">
                                @else
                                    <img src="https://via.placeholder.com/80x50" class="rounded" style="object-fit:cover;">
                                @endif
                            </td>

                            <td>
                                @if ($service->status == 'ACTIVE')
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
                                            <a class="dropdown-item" href="{{ route('admin.services.edit', $service->id) }}">
                                                <i class="ri-pencil-line me-2"></i> Edit
                                            </a>
                                        </li>

                                        <li>
                                            <form method="POST" action="{{ route('admin.services.destroy', $service->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger"
                                                    onclick="return confirm('Delete service?')">
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
                            <td colspan="6" class="text-center">No services found</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
        <!-- Pagination (Dynamic) -->

        <div class="row px-4 pb-3 align-items-center">
            {{ $services->links('pagination::bootstrap-5') }}
        </div>

@endsection