@extends('admin.layouts.master')

@section('title', 'CMS Pages')

@section('content')

<div class="card">

    <!-- ALERT -->
    <div class="p-3">
        @include('admin.layouts.partials.alerts')
    </div>

    <!-- Header -->
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">CMS Pages</h5>

        <div class="d-flex align-items-center gap-3">

            <!-- Search -->
            <form method="GET" action="{{ route('admin.cms_pages.index') }}" class="d-flex align-items-center">
                <span class="me-2">Search:</span>
                <input type="search"
                       name="search"
                       class="form-control form-control-sm"
                       placeholder="Search pages..."
                       value="{{ request('search') }}"
                       style="width:200px;">
            </form>

            <!-- Add Button -->
            <a href="{{ route('admin.cms_pages.create') }}" class="btn btn-primary btn-sm">
                <i class="ri-add-line me-1"></i> Add Page
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
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Content</th>
                    <th width="120">Status</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($pages as $page)
                    <tr>
                        <td>{{ $loop->iteration + ($pages->currentPage() - 1) * $pages->perPage() }}</td>

                        <td>
                            <span class="fw-semibold">{{ $page->title }}</span>
                        </td>

                        <td>{{ $page->slug }}</td>

                        <td style="max-width:250px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                            {{ \Illuminate\Support\Str::limit(strip_tags($page->content), 60) }}
                        </td>

                        <!-- Status -->
                        <td>
                            @if($page->status == 1)
                                <span class="badge rounded-pill bg-label-success">Active</span>
                            @else
                                <span class="badge rounded-pill bg-label-danger">Inactive</span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                                        data-bs-toggle="dropdown">
                                    <i class="ri-more-2-line"></i>
                                </button>

                                <ul class="dropdown-menu dropdown-menu-end">

                                    <!-- Edit -->
                                    <li>
                                        <a class="dropdown-item"
                                           href="{{ route('admin.cms_pages.edit', $page->id) }}">
                                            <i class="ri-pencil-line me-2"></i> Edit
                                        </a>
                                    </li>

                                    <!-- Delete -->
                                    <li>
                                        <form action="{{ route('admin.cms_pages.destroy', $page->id) }}"
                                              method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger"
                                                    onclick="return confirm('Are you sure you want to delete this page?')">
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
                        <td colspan="6" class="text-center">No pages found</td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

    <!-- Pagination -->
    <div class="px-4 pb-3">
        {{ $pages->links('pagination::bootstrap-5') }}
    </div>

</div>

@endsection