@extends('admin.layouts.master')

@section('title', 'Support Details')

@section('content')

    <div class="card">

        <!-- HEADER -->
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Expert Details</h5>

            <a href="{{ route('admin.expert_supports.index') }}" class="btn btn-sm btn-secondary">
                <i class="ri-arrow-left-line me-1"></i> Back
            </a>
        </div>

        <!-- BODY -->
        <div class="card-body">

            <!-- TOP ROW -->
            <div class="d-flex align-items-start gap-3 mb-4">

                <!-- IMAGE -->
                <img src="{{ $support->expert && $support->expert->profile_image
                    ? fileUrl($support->expert->profile_image)
                    : asset('assets/img/default-profile-image.jpg') }}"
                    width="80" height="80" class="rounded-circle" style="object-fit: cover;">

                <!-- DETAILS -->
                <div class="w-100">
                    <!-- ROW 1 -->
                    <div class="row mb-1">
                         <div class="col-md-4">
                            <strong>Name:</strong>
                            <span class="text-muted ms-1">{{ $support->expert->name ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-4">
                            <strong>Email:</strong>
                            <span class="text-muted ms-1">{{ $support->expert->email ?? 'N/A' }}</span>
                        </div>

                        <div class="col-md-4">
                            <strong>Phone:</strong>
                            <span class="text-muted ms-1">{{ $support->expert->phone ?? 'N/A' }}</span>
                        </div>

                      
                    </div>

                    <!-- ROW 2 -->
                    <div class="row">
                          <div class="col-md-4">
                            <strong>Type:</strong>
                            <span class="text-muted ms-1">
                                {{ ucfirst($support->type == 'phone' ? 'Call' : $support->type) }}
                            </span>
                        </div>
                        <div class="col-md-4">
                            <strong>Created Date:</strong>
                            <span class="text-muted ms-1">
                                {{ $support->created_at->format('d M Y, h:i A') }}
                            </span>
                        </div>
                    </div>

                </div>

            </div>

        

            <hr>

            <!-- MESSAGE -->
            <div>
                <strong>Message:</strong>
                <div class="mt-2 p-3 bg-light rounded border">
                    {{ $support->value }}
                </div>
            </div>

        </div>

    </div>

@endsection
