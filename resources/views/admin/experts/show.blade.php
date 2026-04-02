@extends('admin.layouts.master')
@section('title', 'Expert Details')

@section('content')
    <div class="card">

        <!-- ALERT MESSAGE -->
        <div class="p-3">
            @include('admin.layouts.partials.alerts')
        </div>

        <!-- Header -->
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
            <h5 class="card-title mb-2 mb-md-0">Expert Details</h5>

            <a href="{{ route('admin.experts.index') }}" class="btn btn-sm btn-secondary">
                <i class="ri-arrow-left-line me-1"></i> Back
            </a>
        </div>

        <hr class="my-0">

        <!-- Expert Info -->
        <div class="row px-3 px-md-4 py-3 align-items-center">
            <div class="col-12 col-md-2 text-center mb-3 mb-md-0">
                <img src="{{ $expert->profile_image ? asset('storage/' . $expert->profile_image) : asset('assets/img/default-profile-image.jpg') }}"
                    width="80" height="80" class="rounded-circle">
            </div>

            <div class="col-12 col-md-10">
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-4 mb-2"><strong>Name:</strong> {{ $expert->name }}</div>
                    <div class="col-12 col-sm-6 col-md-4 mb-2"><strong>Email:</strong> {{ $expert->email }}</div>
                    <div class="col-12 col-sm-6 col-md-4 mb-2"><strong>Phone:</strong> {{ $expert->phone ?? 'N/A' }}</div>

                   <div class="col-12 col-sm-6 col-md-4 mb-2">
    <strong>Status:</strong>

    @if ($expert->status == 1)
        <span class="badge bg-label-success rounded-pill">Active</span>
    @else
        <span class="badge bg-label-danger rounded-pill">Inactive</span>
    @endif

</div>
                </div>
            </div>
        </div>

        <hr class="my-0">

        <!-- ACCORDION -->
        <div class="accordion px-2 px-md-4 pb-3" id="expertAccordion">

            <!-- Rating -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse"
                        data-bs-target="#rating">
                        Expert Rating
                    </button>
                </h2>
                <div id="rating" class="accordion-collapse collapse" data-bs-parent="#expertAccordion">
                    <div class="accordion-body">
                        <div class="row text-sm">
                            <div class="col-12 col-md-4 mb-2">
                                <strong>Average Rating:</strong>
                                {{ optional($expert->ratingStat)->avg_rating ?? 0 }}
                            </div>

                            <div class="col-12 col-md-4 mb-2">
                                <strong>Total Reviews:</strong>
                                {{ optional($expert->ratingStat)->total_reviews ?? 0 }}
                            </div>

                            <div class="col-12 col-md-4 mb-2">
                                <strong>Stars:</strong>
                                @php $avg = optional($expert->ratingStat)->avg_rating ?? 0; @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $avg)
                                        <span class="text-warning">★</span>
                                    @else
                                        <span class="text-muted">☆</span>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Details -->
            <div class="accordion-item mt-2">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse"
                        data-bs-target="#details">
                        Expert Additional Details
                    </button>
                </h2>
                <div id="details" class="accordion-collapse collapse" data-bs-parent="#expertAccordion">
                    <div class="accordion-body">

                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Reg Code</th>
                                        <th>Approval</th>
                                        <th>Training Center</th>

                                        <th>Online</th>
                                        <th>Updated</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($expert->expertDetail ? [$expert->expertDetail] : [] as $key => $detail)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $detail->registration_code ?? '-' }}</td>
                                            <td>{{ $detail->approval_status ?? '-' }}</td>
                                            <td>{{ $detail->trainingCenter->name ?? '-' }}</td>
                                            <td>
                                                @if ($detail->is_online == 1)
                                                    <span class="badge bg-success">Yes</span>
                                                @else
                                                    <span class="badge bg-secondary">No</span>
                                                @endif
                                            </td>
                                            <td>{{ $detail->updated_at }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No Details Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Emergency Contacts -->
            <div class="accordion-item mt-2">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse"
                        data-bs-target="#contacts">
                        Emergency Contacts
                    </button>
                </h2>
                <div id="contacts" class="accordion-collapse collapse" data-bs-parent="#expertAccordion">
                    <div class="accordion-body">

                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Updated</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(optional($expert->expertDetail)->emergencyContacts ?? [] as $key => $contact)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $contact->name ?? '-' }}</td>
                                            <td>{{ $contact->phone ?? '-' }}</td>
                                            <td>{{ $contact->updated_at }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No Emergency Contacts Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
