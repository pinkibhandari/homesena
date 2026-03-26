@extends('admin.layouts.master')

@section('title', 'Change Password')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">
        <div class="card shadow-lg rounded-4 overflow-hidden">

            <!-- Card Header -->
            <div class="card-header bg-primary text-white text-center">
                <h5 class="mb-0 d-flex justify-content-center align-items-center text-white">
                    <i class="ri-lock-line me-2"></i> Change Password
                </h5>
            </div>

            <!-- Card Body -->
            <div class="card-body p-4">

                <!-- Success Message -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Error Message -->
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('admin.update_password') }}" method="POST">
                    @csrf

                    <!-- Current Password -->
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="ri-key-line"></i></span>
                            <input type="password" id="current_password" name="current_password" class="form-control" placeholder="Enter current password" required>
                            <button type="button" class="btn btn-light" onclick="togglePassword('current_password', 'icon_current')">
                                <i class="ri-eye-line" id="icon_current"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="ri-lock-line"></i></span>
                            <input type="password" id="new_password" name="password" class="form-control" placeholder="Enter new password" required>
                            <button type="button" class="btn btn-light" onclick="togglePassword('new_password', 'icon_new')">
                                <i class="ri-eye-line" id="icon_new"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="ri-lock-password-line"></i></span>
                            <input type="password" id="confirm_password" name="password_confirmation" class="form-control" placeholder="Confirm new password" required>
                            <button type="button" class="btn btn-light" onclick="togglePassword('confirm_password', 'icon_confirm')">
                                <i class="ri-eye-line" id="icon_confirm"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                             Update Password
                        </button>
                    </div>
                </form>

            </div>


        </div>
    </div>
</div>

<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);

    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("ri-eye-line");
        icon.classList.add("ri-eye-off-line");
    } else {
        input.type = "password";
        icon.classList.remove("ri-eye-off-line");
        icon.classList.add("ri-eye-line");
    }
}
</script>
@endsection