@extends('admin.layouts.master')

@section('title', 'Support Details')

@section('content')

    <div class="card p-4">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
            <h5 class="card-title mb-2 mb-md-0">Support Ticket Details</h5>

            <a href="{{ route('admin.user_supports.index') }}" class="btn btn-sm btn-secondary">
                <i class="ri-arrow-left-line me-1"></i> Back
            </a>
        </div>
        
        <hr>

        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Name:</strong>
                <p>{{ $support->name }}</p>
            </div>

            <div class="col-md-6">
                <strong>Email:</strong>
                <p>{{ $support->email }}</p>
            </div>

            <div class="col-md-6">
                <strong>Phone:</strong>
                <p>{{ $support->phone }}</p>
            </div>

            <div class="col-md-6">
                <strong>Status:</strong>
                <p>
                    @if ($support->status == 'pending')
                        <span class="badge bg-warning">Pending</span>
                    @else
                        <span class="badge bg-success">Resolved</span>
                    @endif
                </p>
            </div>
        </div>

        <hr>

        <!-- MESSAGE -->
        <div class="mb-3">
            <strong>Message:</strong>
            <p class="mt-2">{{ $support->message ?? 'No message provided' }}</p>
        </div>

        <!-- FILE (IMAGE / VIDEO) -->
        @if ($support->file)
            <div class="mb-3">
                <strong>Attachment:</strong>
                <div class="mt-2">

                    @php
                        $ext = pathinfo($support->file, PATHINFO_EXTENSION);
                    @endphp

                    {{-- IMAGE --}}
                    @if (in_array($ext, ['jpg', 'jpeg', 'png']))
                        <img src="{{ asset('uploads/support/' . $support->file) }}" width="250" class="img-thumbnail">

                        {{-- VIDEO --}}
                    @elseif(in_array($ext, ['mp4', 'mov', 'avi']))
                        <video width="300" controls>
                            <source src="{{ asset('uploads/support/' . $support->file) }}">
                        </video>
                    @else
                        <p>File: {{ $support->file }}</p>
                    @endif

                </div>
            </div>
        @endif

    </div>

@endsection
