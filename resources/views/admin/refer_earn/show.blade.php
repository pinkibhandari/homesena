@extends('admin.layouts.master')
@section('title', 'Referral Details')

@section('content')

<div class="card">

    <!-- ALERT -->
    <div class="p-3">
        @include('admin.layouts.partials.alerts')
    </div>

    <!-- HEADER -->
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
        <h5 class="card-title mb-2 mb-md-0">Referral Details</h5>

        <a href="{{ route('admin.refer_earn.index') }}" class="btn btn-sm btn-secondary">
            <i class="ri-arrow-left-line me-1"></i> Back
        </a>
    </div>

    <hr class="my-0">

    <!-- USER BASIC INFO -->
    <div class="row px-3 px-md-4 py-3 align-items-center">

        <!-- Profile Image -->
        <div class="col-12 col-md-2 text-center mb-3 mb-md-0">
            <img src="{{ $user->profile_image ? asset($user->profile_image) : asset('default.png') }}"
                width="80" height="80"
                class="rounded-circle border shadow-sm"
                style="object-fit: cover;">
        </div>

        <div class="col-12 col-md-10">
            <div class="row">

                <div class="col-12 col-sm-6 col-md-4 mb-2">
                    <strong>Name:</strong> {{ $user->name }}
                </div>

                <div class="col-12 col-sm-6 col-md-4 mb-2">
                    <strong>Email:</strong> {{ $user->email }}
                </div>

                <div class="col-12 col-sm-6 col-md-4 mb-2">
                    <strong>Phone:</strong> {{ $user->phone }}
                </div>

                <div class="col-12 col-sm-6 col-md-4 mb-2">
                    <strong>Referral Code:</strong>
                    <span class="badge bg-label-info">{{ $user->referral_code }}</span>
                </div>

                <div class="col-12 col-sm-6 col-md-4 mb-2">
                    <strong>Referred By:</strong>
                    {{ $user->referrer->name ?? 'Direct Signup' }}
                </div>

                <div class="col-12 col-sm-6 col-md-4 mb-2">
                    <strong>Total Referrals:</strong>
                    <span class="badge bg-label-dark">
                        {{ $user->referrals->count() }}
                    </span>
                </div>

                <div class="col-12 col-sm-6 col-md-4 mb-2">
                    <strong>Reward Status:</strong>
                    @if($user->referral_reward_given)
                        <span class="badge bg-label-success">Given</span>
                    @else
                        <span class="badge bg-label-warning">Pending</span>
                    @endif
                </div>

                <div class="col-12 col-sm-6 col-md-4 mb-2">
                    <strong>Created At:</strong>
                    {{ optional($user->created_at)->format('d M Y') }}
                </div>

                <div class="col-12 col-sm-6 col-md-4 mb-2">
                    <strong>Updated At:</strong>
                    {{ optional($user->updated_at)->format('d M Y') }}
                </div>

            </div>
        </div>

    </div>

    <hr class="my-0">

    <!-- ACCORDION -->
    <div class="accordion px-2 px-md-4 pb-3" id="referralAccordion">

        <!-- Referred Users -->
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed fw-semibold" type="button"
                    data-bs-toggle="collapse" data-bs-target="#referrals">
                    Referred Users
                </button>
            </h2>

            <div id="referrals" class="accordion-collapse collapse" data-bs-parent="#referralAccordion">
                <div class="accordion-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">

                            <thead class="bg-label-secondary">
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Joined</th>
                                </tr>
                            </thead>

                            <tbody>
                               
                                @forelse($user->referrals as $index => $ref)
                                
                                    <tr>
                                        <td>{{ $index + 1 }}</td>

                                        <td class="d-flex align-items-center gap-2">
                                            <img src="{{ $ref->profile_image ? asset($ref->profile_image) : asset('default.png') }}"
                                                width="35" height="35" class="rounded-circle">
                                            {{ $ref->name }}
                                        </td>

                                        <td>{{ $ref->email }}</td>

                                        <td>
                                            {{ optional($ref->created_at)->format('d M Y') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No referrals found</td>
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