@extends('admin.layouts.master')

@section('title', 'Time Slots')

@section('content')

<div class="card">

    <!-- ALERT -->
    <div class="p-3">
        @include('admin.layouts.partials.alerts')
    </div>

    <!-- Header -->
    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="card-title mb-0">Time Slots List</h5>

        <div class="d-flex align-items-center gap-3">

            <!-- Search -->
            <form method="GET" action="{{ route('admin.time_slots.index') }}" class="d-flex align-items-center">
                <span class="me-2">Search:</span>
                <input type="search" name="search" value="{{ request('search') }}"
                    class="form-control form-control-sm"
                    placeholder="Search time..." style="width:200px;">
            </form>

            <!-- Add Button -->
            <a href="{{ route('admin.time_slots.create') }}" class="btn btn-primary btn-sm">
                <i class="ri-add-line me-1"></i> Add Slot
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
                    <th>Time (AM/PM)</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($slots as $slot)
                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td>
                            <span class="fw-semibold">
                                {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }}
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
                                            href="{{ route('admin.time_slots.edit', $slot->id) }}">
                                            <i class="ri-pencil-line me-2"></i> Edit
                                        </a>
                                    </li>

                                    <li>
                                        <form method="POST"
                                            action="{{ route('admin.time_slots.destroy', $slot->id) }}">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="dropdown-item text-danger"
                                                onclick="return confirm('Delete this slot?')">
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
                        <td colspan="3" class="text-center">No time slots found</td>
                    </tr>
                @endforelse
            </tbody>

        </table>

    </div>

    <!-- Pagination -->
    <div class="px-4 pb-3 d-flex justify-content-between align-items-center">
        <small class="text-muted">
            Showing {{ $slots->firstItem() ?? 0 }} to {{ $slots->lastItem() ?? 0 }}
            of {{ $slots->total() ?? 0 }} entries
        </small>

        {{ $slots->links('pagination::bootstrap-5') }}
    </div>

</div>

@endsection