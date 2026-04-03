@extends('admin.layouts.master')

@section('title', 'Service Details')

@section('content')
<style>
    .description-box {
        max-height: 250px;
        overflow-y: auto;
        padding: 10px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        background: #fafafa;
        font-size: 14px;
        line-height: 1.6;
    }

    .description-box img {
        max-width: 100%;
        height: auto;
        border-radius: 6px;
    }

    .description-box table {
        width: 100%;
        display: block;
        overflow-x: auto;
    }

    .description-box p {
        margin-bottom: 8px;
    }
</style>

    <div class="card">

        <!-- ALERT -->
        <div class="p-3">
            @include('admin.layouts.partials.alerts')
        </div>

        <!-- HEADER -->
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
            <h5 class="card-title mb-2 mb-md-0">Service Details</h5>

            <a href="{{ route('admin.services.index') }}" class="btn btn-sm btn-secondary">
                <i class="ri-arrow-left-line me-1"></i> Back
            </a>
        </div>

        <hr class="my-0">

        <!-- SERVICE BASIC INFO -->
        <div class="row px-3 px-md-4 py-3 align-items-center">

            <!-- Service Image -->
            <div class="col-12 col-md-2 text-center mb-3 mb-md-0">
                <img src="{{ !empty($service->image)
                    ? asset('storage/' . $service->image)
                    : asset('assets/img/default-profile-image.jpg') }}"
                    alt="Service Image" width="80" height="80" class="rounded-circle border shadow-sm"
                    style="object-fit: cover;">
            </div>

            <div class="col-12 col-md-10">
                <div class="row">

                    <div class="col-12 col-sm-6 col-md-4 mb-2">
                        <strong>Name:</strong> {{ $service->name }}
                    </div>

                    <div class="col-12 col-sm-6 col-md-4 mb-2">
                        <strong>Slug:</strong> {{ $service->slug }}
                    </div>

                    <div class="col-12 col-sm-6 col-md-4 mb-2">
                        <strong>Status:</strong>
                        @if ($service->status == 1)
                            <span class="badge bg-label-success rounded-pill">Active</span>
                        @else
                            <span class="badge bg-label-danger rounded-pill">Inactive</span>
                        @endif
                    </div>

                    <div class="col-12 col-sm-6 col-md-4 mb-2">
                        <strong>New:</strong>
                        @if ($service->new_flag == 1)
                            <span class="badge bg-label-primary rounded-pill">Yes</span>
                        @else
                            <span class="badge bg-label-secondary rounded-pill">No</span>
                        @endif
                    </div>

                    <div class="col-12 col-sm-6 col-md-4 mb-2">
                        <strong>Created At:</strong> {{ $service->created_at }}
                    </div>

                    <div class="col-12 col-sm-6 col-md-4 mb-2">
                        <strong>Updated At:</strong> {{ $service->updated_at }}
                    </div>

                </div>
            </div>
        </div>

        <hr class="my-0">

        <!-- ACCORDION -->
        <div class="accordion px-2 px-md-4 pb-3" id="serviceAccordion">

            <!-- Description -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse"
                        data-bs-target="#description">
                        Description
                    </button>
                </h2>

                <div id="description" class="accordion-collapse collapse" data-bs-parent="#serviceAccordion">
                    <div class="accordion-body">

                        <div class="description-box">
                            {!! $service->description ?? '<p class="text-muted">No Description</p>' !!}
                        </div>

                    </div>
                </div>
            </div>

            <!-- Slider Details -->
            <div class="accordion-item mt-2">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse"
                        data-bs-target="#slider">
                        Slider Details
                    </button>
                </h2>

                <div id="slider" class="accordion-collapse collapse" data-bs-parent="#serviceAccordion">
                    <div class="accordion-body">

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <strong>Slider Title:</strong>
                                <p>{{ $service->slider_title ?? '-' }}</p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>Slider Description:</strong>
                                <p>{{ $service->slider_description ?? '-' }}</p>
                            </div>

                            <!-- Slider Image -->
                            <div class="col-md-6 mb-3">
                                <strong>Slider Image:</strong><br>

                                @if (!empty($service->slider_image))
                                    <!-- Thumbnail -->
                                    <img src="{{ asset('storage/' . $service->slider_image) }}" alt="Slider Image"
                                        class="rounded border shadow-sm"
                                        style="width:150px; height:100px; object-fit:cover; cursor:pointer;"
                                        data-bs-toggle="modal" data-bs-target="#imageModal">

                                    <!-- Modal -->
                                    <div class="modal fade" id="imageModal" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">

                                                <div class="modal-header">
                                                    <h5 class="modal-title">Slider Image</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body text-center">
                                                    <img src="{{ asset('storage/' . $service->slider_image) }}"
                                                        class="rounded" style="max-width:250px; width:100%; height:auto;">
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-muted">No Image</p>
                                @endif

                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection


