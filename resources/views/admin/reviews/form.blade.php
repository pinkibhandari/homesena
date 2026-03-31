@extends('admin.layouts.master')

@section('title', 'Edit Review')

@section('content')

<div class="card">

    <!-- ALERT -->
    <div class="p-3">
        @include('admin.layouts.partials.alerts')
    </div>

    <!-- Header -->
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit Review</h5>
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-sm btn-light">
            <i class="ri-arrow-left-line me-1"></i> Back
        </a>
    </div>

    <hr class="my-0">

    <div class="card-body">

        <form method="POST" action="{{ route('admin.reviews.update', $review->id) }}">
            @csrf
            @method('PUT')

            <div class="row g-4">

                <!-- ⭐ Rating -->
                <div class="col-lg-4 col-md-6 col-12">
                    <label class="form-label fw-semibold">Rating</label>

                    <select name="rating"
                        class="form-select @error('rating') is-invalid @enderror">
                        <option value="">Select Rating</option>

                        @for($i=1; $i<=5; $i++)
                            <option value="{{ $i }}" {{ $review->rating == $i ? 'selected' : '' }}>
                                ⭐ {{ $i }} Star
                            </option>
                        @endfor
                    </select>

                    @error('rating')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 👍 Recommend (Switch UI) -->
                <div class="col-lg-4 col-md-6 col-12">
                    <label class="form-label fw-semibold d-block">Would Recommend</label>

                    <div class="form-check form-switch mt-2">
                        <input 
                            class="form-check-input"
                            type="checkbox"
                            name="would_recommend"
                            value="1"
                            style="transform: scale(1.4);"
                            {{ $review->would_recommend ? 'checked' : '' }}>
                    </div>

                    <!-- hidden for unchecked -->
                    <input type="hidden" name="would_recommend" value="0">
                </div>

                <!-- ✍️ Review -->
                <div class="col-12">
                    <label class="form-label fw-semibold">Review</label>

                    <textarea name="review"
                        rows="4"
                        class="form-control @error('review') is-invalid @enderror"
                        placeholder="Write review here..."
                        style="resize:none;">{{ old('review', $review->review) }}</textarea>

                    @error('review')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <!-- Buttons -->
            <div class="mt-4 d-flex flex-wrap justify-content-end gap-2">
             

                <button type="submit" class="btn btn-primary">
                    <i class="ri-save-line me-1"></i> Update Review
                </button>
            </div>

        </form>

    </div>
</div>

@endsection