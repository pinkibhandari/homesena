@extends('admin.layouts.master')

@section('title', 'Service Variants')

@section('content')

    <div class="card">

        <!-- ALERT -->
        <div class="p-3">
            @include('admin.layouts.partials.alerts')
        </div>

        <!-- Header -->
        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="card-title mb-0">Service Variants List</h5>

            <div class="d-flex align-items-center gap-3">

                <!-- Search -->
                <form method="GET" action="{{ route('admin.service_variants.index') }}" class="d-flex align-items-center">
                    <span class="me-2">Search:</span>
                    <input type="search" name="search" value="{{ request('search') }}" class="form-control form-control-sm"
                        placeholder="Search variants..." style="width:200px;">
                </form>

                <!-- Add Button -->
                <a href="{{ route('admin.service_variants.create') }}" class="btn btn-primary btn-sm">
                    <i class="ri-add-line me-1"></i> Add Variant
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
                        <th>Duration</th>
                        <th>Price</th>
                        <th>Discount</th>
                        <th width="120">Status</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($variants as $variant)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                <span class="fw-semibold">
                                    {{ $variant->service->name ?? '-' }}
                                </span>
                            </td>

                            <td>
                                {{ $variant->duration_minutes }} min
                            </td>

                            <td>
                                ₹ {{ number_format($variant->base_price, 2) }}
                            </td>

                            <td>
                                ₹ {{ $variant->discount_price ?? '-' }}
                            </td>

                            <td>
                                @if ($variant->is_active)
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
                                                href="{{ route('admin.service_variants.edit', $variant->id) }}">
                                                <i class="ri-pencil-line me-2"></i> Edit
                                            </a>
                                        </li>

                                        <li>
                                            <form method="POST"
                                                action="{{ route('admin.service_variants.destroy', $variant->id) }}">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="dropdown-item text-danger"
                                                    onclick="return confirm('Delete this variant?')">
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
                            <td colspan="7" class="text-center">No variants found</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>

        <!-- Pagination -->
        <div class="px-4 pb-3 d-flex justify-content-between align-items-center">
            <small class="text-muted">
                Showing {{ $variants->firstItem() ?? 0 }} to {{ $variants->lastItem() ?? 0 }}
                of {{ $variants->total() ?? 0 }} entries
            </small>

            {{ $variants->links('pagination::bootstrap-5') }}
        </div>

    </div>

@endsection
