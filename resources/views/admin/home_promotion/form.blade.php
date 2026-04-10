@extends('admin.layouts.master')
@section('title', $home_promotion->id ? 'Edit Promotion' : 'Add Promotion')

@section('content')

    <div class="card">

        <!-- Header -->
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                {{ $home_promotion->id ? 'Edit Home Promotion' : 'Add Home Promotion' }}
            </h5>
            <a href="{{ route('admin.home_promotion.index') }}" class="btn btn-sm btn-light">
                <i class="ri-arrow-left-line me-1"></i> Back
            </a>
        </div>

        <div class="card-body">
            <form id="serviceForm" method="POST"
                action="{{ $home_promotion->id ? route('admin.home_promotion.update', $home_promotion->id) : route('admin.home_promotion.store') }}"
                enctype="multipart/form-data">

                @csrf
                @if ($home_promotion->id)
                    @method('PUT')
                @endif

                <div class="row g-3">

                    <!-- Title -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <label class="form-label">Title</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-service-line"></i></span>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                placeholder="Enter Title" value="{{ old('title', $home_promotion->title) }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Image -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <label class="form-label">Image</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-image-line"></i></span>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @if ($home_promotion->image)
                            <img src="{{ asset($home_promotion->image) }}" class="mt-2 rounded"
                                style="width:80px; height:50px; object-fit:cover;">
                        @endif
                    </div>
                    <!-- DATE TIME -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <label class="form-label">Date & Time</label>
                        <input type="datetime-local" name="promotion_datetime" class="form-control"
                            value="{{ old(
                                'promotion_datetime',
                                $home_promotion->promotion_datetime
                                    ? \Carbon\Carbon::parse($home_promotion->promotion_datetime)->format('Y-m-d\TH:i')
                                    : '',
                            ) }}">
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

                                <option value="1" {{ old('status', $home_promotion->status) == 1 ? 'selected' : '' }}>
                                    ACTIVE
                                </option>

                                <option value="0" {{ old('status', $home_promotion->status) == 0 ? 'selected' : '' }}>
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
                            value="{{ old('description', $home_promotion->description) }}">
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>


                </div>

                <div class="mt-4">
                    <button class="btn btn-primary">
                        <i class="ri-save-line me-1"></i>
                        {{ $home_promotion->id ? 'Update' : 'Save' }}
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
