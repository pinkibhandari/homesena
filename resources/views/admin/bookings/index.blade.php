@extends('admin.layouts.master')
@section('title', 'Booking Table')
@section('content')
    <div class="card">
          <!-- ALERT MESSAGE -->
    <div class="p-3">
        @include('admin.layouts.partials.alerts')
    </div>
        <!-- Header -->
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Booking Table</h5>
            <div class="d-flex align-items-center gap-3">
                <!-- Search -->
                <form method="GET" action="{{ route('admin.users.index') }}" class="d-flex align-items-center">
                    <div class="d-flex align-items-center">
                        <span class="me-2">Search:</span>
                        <input name="search" type="search" class="form-control form-control-sm" placeholder="Search users..."
                            value="{{ request('search') }}" style="width:200px;">
                    </div>
                    <!-- <select name="status" class="form-select form-select-sm">
                                <option value="">All</option>
                                <option value="1" {{ request('status')=='1'?'selected':'' }}>Active</option>
                                <option value="0" {{ request('status')=='0'?'selected':'' }}>Inactive</option>
                            </select> -->
                </form>
                <!-- Add Button -->
                <!-- <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                    <i class="ri-add-line me-1"></i> Add User
                </a> -->
            </div>
        </div>
        <hr class="my-0">
        <!-- Show Entries -->
        <div class="row px-4 py-3 align-items-center">
            <!-- <div class="col-md-6">
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
                        </div> -->
        </div>
        <!-- Table -->
        <div class="table-responsive px-4 pb-3">
            <table class="table table-hover align-middle table-bordered">
                <thead class="bg-label-secondary">
                    <tr>
                        <th width="60">ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th width="120">Status</th>
                        <!-- <th width="120">Actions</th> -->
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <span class="fw-semibold">{{ $user->name ?? ' ' }}</span>
                            </td>
                            <td>{{ $user->phone }}</td>
                            <td>
                                @if($user->status === 'ACTIVE')
                                    <span class="badge rounded-pill bg-label-success">ACTIVE</span>
                                @else
                                    <span class="badge rounded-pill bg-label-danger">INACTIVE</span>
                                @endif
                            </td>
                            <!-- <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                                        data-bs-toggle="dropdown">
                                        <i class="ri-more-2-line"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.users.edit', $user->id) }}">
                                                <i class="ri-pencil-line me-2"></i>
                                                Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this user?')" type="submit" >
                                                    <i class="ri-delete-bin-6-line me-2"></i> Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td> -->
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
            <!-- <div class="col-md-6">
                    <small class="text-muted">
                        Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries
                    </small>
                </div> -->
            <!-- <div class="col-md-6 text-end"> -->
            {{ $users->links('pagination::bootstrap-5') }}

            <!-- </div> -->
    </div>
@endsection