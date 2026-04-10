@extends('admin.layouts.master')

@section('title', $service->id ? 'Edit Service' : 'Create Service')

@section('content')

    <div class="card">

        <!-- Header -->
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                {{ $service->id ? 'Edit Service' : 'Create Service' }}
            </h5>
            <a href="{{ route('admin.services.index') }}" class="btn btn-sm btn-light">
                <i class="ri-arrow-left-line me-1"></i> Back
            </a>
        </div>

        <div class="card-body">
            <form id="serviceForm" method="POST"
                action="{{ $service->id ? route('admin.services.update', $service->id) : route('admin.services.store') }}"
                enctype="multipart/form-data">

                @csrf
                @if ($service->id)
                    @method('PUT')
                @endif

                <div class="row g-3">

                    <!-- Service Name -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <label class="form-label">Service Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-service-line"></i></span>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                placeholder="Enter service name" value="{{ old('name', $service->name) }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Service Image -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <label class="form-label">Service Image</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-image-line"></i></span>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @if ($service->image)
                            <img src="{{ asset($service->image) }}" class="mt-2 rounded"
                                style="width:80px; height:50px; object-fit:cover;">
                        @endif
                    </div>

                    <!-- Status -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <label class="form-label">Status</label>

                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-shield-check-line"></i>
                            </span>

                            <select name="status" class="form-select @error('status') is-invalid @enderror">

                                <option value="">Select status</option>

                                <option value="1" {{ old('status', $service->status) == 1 ? 'selected' : '' }}>
                                    ACTIVE
                                </option>

                                <option value="0" {{ old('status', $service->status) == 0 ? 'selected' : '' }}>
                                    INACTIVE
                                </option>

                            </select>

                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                   

                    <!-- Description -->
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <div id="editor" style="height: 200px;"></div>
                        <input type="hidden" name="description" id="description"
                            value="{{ old('description', $service->description) }}">
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Slider Section -->
                    <div class="col-12 mt-4">
                        <h6 class="fw-bold mb-3">Service Slider</h6>
                    </div>

                    <!-- Slider Image -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <label class="form-label">Slider Image</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-image-add-line"></i></span>
                            <input type="file" name="slider_image"
                                class="form-control @error('slider_image') is-invalid @enderror">
                            @error('slider_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @if ($service->slider_image)
                            <img src="{{ asset( $service->slider_image) }}" class="mt-2 rounded"
                                style="width:80px; height:50px; object-fit:cover;">
                        @endif
                    </div>
                    <!-- Slider Title -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <label class="form-label">Slider Title</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-text"></i></span>
                            <input type="text" name="slider_title"
                                class="form-control @error('slider_title') is-invalid @enderror"
                                placeholder="Enter slider title"
                                value="{{ old('slider_title', $service->slider_title) }}">
                            @error('slider_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Slider Description -->
                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <label class="form-label">Slider Description</label>
                        <textarea name="slider_description" class="form-control @error('slider_description') is-invalid @enderror"
                            rows="2" placeholder="Enter slider description">{{ old('slider_description', $service->slider_description) }}</textarea>
                        @error('slider_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="mt-4">
                    <button class="btn btn-primary">
                        <i class="ri-save-line me-1"></i>
                        {{ $service->id ? 'Update Service' : 'Save Service' }}
                    </button>
                </div>

            </form>
        </div>

    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Quill
            var quill = new Quill('#editor', {
                theme: 'snow',
                placeholder: 'Write Description here...',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{
                            'list': 'ordered'
                        }, {
                            'list': 'bullet'
                        }],
                        ['link', 'image']
                    ]
                }
            });

            // Load existing description for edit
            var descriptionInput = document.getElementById('description');
            if (descriptionInput.value) {
                quill.root.innerHTML = descriptionInput.value;
            }

            // On form submit, copy content to hidden input
            var form = document.getElementById('serviceForm');
            form.addEventListener('submit', function() {
                descriptionInput.value = quill.root.innerHTML.trim();
            });
        });
    </script>
@endpush
