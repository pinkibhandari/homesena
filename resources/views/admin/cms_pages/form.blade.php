@extends('admin.layouts.master')

@section('title', $page->id ? 'Edit Page' : 'Create Page')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ $page->id ? 'Edit Page' : 'Create Page' }}</h5>
            <a href="{{ route('admin.cms_pages.index') }}" class="btn btn-sm btn-light">
                <i class="ri-arrow-left-line me-1"></i> Back
            </a>
        </div>

        <div class="card-body">
            <form id="cmsPageForm" method="POST"
                action="{{ $page->id ? route('admin.cms_pages.update', $page->id) : route('admin.cms_pages.store') }}">

                @csrf
                @if ($page->id)
                    @method('PUT')
                @endif

                <div class="row g-3">

                    <!-- Title -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <label class="form-label">Page Title</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                            placeholder="Enter page title" value="{{ old('title', $page->title) }}">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Type -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror">
                            <option value="">Select Type</option>
                            <option value="user" {{ old('type', $page->type) == 'user' ? 'selected' : '' }}>User</option>
                            <option value="expert" {{ old('type', $page->type) == 'expert' ? 'selected' : '' }}>Expert
                            </option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Status -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="">Select status</option>
                            <option value="1" {{ old('status', $page->status) == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status', $page->status) == 0 ? 'selected' : '' }}>Inactive
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Content -->
                    <div class="col-12">
                        <label class="form-label">Content</label>
                        <div id="editor" style="height: 250px;"></div>
                        <input type="hidden" name="content" id="content" value="{{ old('content', $page->content) }}">
                        @error('content')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        {{ $page->id ? 'Update Page' : 'Save Page' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Quill -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Quill
            var quill = new Quill('#editor', {
                theme: 'snow',
                placeholder: 'Write page content here...',
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

            // Load old/existing content
            var contentInput = document.getElementById('content');
            if (contentInput.value) {
                quill.root.innerHTML = contentInput.value;
            }

            // On form submit, update hidden input
            var form = document.getElementById('cmsPageForm');
            form.addEventListener('submit', function() {
                contentInput.value = quill.root.innerHTML.trim();
            });
        });
    </script>
@endsection
