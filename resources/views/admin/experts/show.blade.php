@extends('admin.layouts.master')
@section('title', 'Expert Details')

@section('content')
<div class="card">

    <!-- ALERT MESSAGE -->
    <div class="p-3">
        @include('admin.layouts.partials.alerts')
    </div>

    <!-- Header -->
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Expert Details</h5>

        <a href="{{ route('admin.experts.index') }}" class="btn btn-sm btn-secondary">
            <i class="ri-arrow-left-line me-1"></i> Back
        </a>
    </div>

    <hr class="my-0">

    <!-- Expert Info -->
    <div class="row px-4 py-3">
        <div class="col-md-2">
            <img src="{{ $expert->profile_image ? asset('storage/' . $expert->profile_image) : asset('assets/img/default-profile-image.jpg') }}"
                width="80" height="80" class="rounded-circle">
        </div>

        <div class="col-md-10">
            <div class="row">
                <div class="col-md-4 mb-2"><strong>Name:</strong> {{ $expert->name }}</div>
                <div class="col-md-4 mb-2"><strong>Email:</strong> {{ $expert->email }}</div>
                <div class="col-md-4 mb-2"><strong>Phone:</strong> {{ $expert->phone ?? 'N/A' }}</div>

                <div class="col-md-4 mb-2">
                    <strong>Status:</strong>
                    @if ($expert->status === 'ACTIVE')
                        <span class="badge bg-label-success rounded-pill">Active</span>
                    @else
                        <span class="badge bg-label-danger rounded-pill">Inactive</span>
                    @endif
                </div>

                <div class="col-md-4 mb-2">
                    <strong>Experience:</strong> {{ $expert->experience ?? 'N/A' }}
                </div>

                <div class="col-md-4 mb-2">
                    <strong>Category:</strong> {{ $expert->category->name ?? 'N/A' }}
                </div>
            </div>
        </div>
    </div>

    <hr class="my-0">

    <!-- Rating Section -->
    <div class="card-header">
        <h6 class="mb-0">Expert Rating</h6>
    </div>

    <div class="row px-4 py-3">
        <div class="col-md-4 mb-2">
            <strong>Average Rating:</strong> 
            {{ optional($expert->ratingStat)->avg_rating ?? 0 }}
        </div>

        <div class="col-md-4 mb-2">
            <strong>Total Reviews:</strong> 
            {{ optional($expert->ratingStat)->total_reviews ?? 0 }}
        </div>

        <div class="col-md-4 mb-2">
            <strong>Stars:</strong>
            @php $avg = optional($expert->ratingStat)->avg_rating ?? 0; @endphp
            @for($i = 1; $i <= 5; $i++)
                @if($i <= $avg)
                    <span class="text-warning">★</span>
                @else
                    <span class="text-muted">☆</span>
                @endif
            @endfor
        </div>
    </div>

    <hr class="my-0">

    <!-- Expert Additional Details -->
    <div class="card-header">
        <h6 class="mb-0">Expert Additional Details</h6>
    </div>

    <div class="table-responsive px-4 pb-3">
        <table class="table table-hover align-middle table-bordered">
            <thead class="bg-label-secondary">
                <tr>
                    <th>#</th>
                    <th>Field</th>
                    <th>Value</th>
                    <th>Updated At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expert->expertDetail ? [$expert->expertDetail] : [] as $key => $detail)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $detail->field_name ?? '-' }}</td>
                        <td>{{ $detail->value ?? '-' }}</td>
                        <td>{{ $detail->updated_at }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No Details Found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection