<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password - HomeSena</title>
<link rel="icon" type="image/svg+xml" href="{{ asset('assets/img/favicon.png') }}">
    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: sans-serif;
            background: #f4f4f4;
        }

        .top-section {
            background: linear-gradient(135deg, #7b2ff7, #5f2c82);
            height: 220px;
            color: white;
            text-align: center;
            padding-top: 40px;
            border-bottom-left-radius: 60% 20%;
            border-bottom-right-radius: 60% 20%;
        }

        .reset-card {
            width: 420px;
            border-radius: 14px;
            margin: -80px auto 0;
            position: relative;
            z-index: 10;
        }

        .form-control {
            height: 45px;
            border-radius: 8px;
        }

        .input-group-text {
            cursor: pointer;
        }

        .btn-reset {
            background: linear-gradient(135deg, #7b2ff7, #5f2c82);
            border: none;
            border-radius: 8px;
            font-weight: 500;
            color: #fff;
        }

        .btn-reset:hover {
            opacity: 0.9;
        }
    </style>
</head>

<body>

    <!-- Top Section -->
    <div class="top-section">
        <h4>HomeSena</h4>
        <p class="mt-2">Set your new password securely</p>
    </div>

    <!-- Reset Password Card -->
    <div class="card shadow reset-card border-0">
        <div class="card-body p-4">

            <h4 class="text-center fw-bold mb-3">Reset Password</h4>

            {{-- Display session errors --}}
            @if(session('error'))
                <p class="text-danger text-center">{{ session('error') }}</p>
            @endif

            {{-- Validation errors --}}
            @if ($errors->any())
                <div class="alert alert-danger p-2">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li style="font-size: 14px;">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.resetPasswordSubmit') }}" onsubmit="disableBtn()">
                @csrf

                <!-- Hidden Email -->
                <input type="hidden" name="email" value="{{ session('email') }}">

                <!-- New Password -->
                <div class="mb-3">
                    <label>New Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="ri-lock-2-line"></i>
                        </span>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter new password" required>
                        <span class="input-group-text bg-white" onclick="togglePassword('password', this)">
                            <i class="ri-eye-line"></i>
                        </span>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <label>Confirm Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="ri-lock-2-line"></i>
                        </span>
                        <input type="password" id="confirm_password" name="password_confirmation" class="form-control" placeholder="Confirm new password" required>
                        <span class="input-group-text bg-white" onclick="togglePassword('confirm_password', this)">
                            <i class="ri-eye-line"></i>
                        </span>
                    </div>
                </div>

                <!-- Reset Button -->
                <button id="resetBtn" type="submit" class="btn btn-reset w-100 py-2">
                    <i class="ri-refresh-line me-1"></i> Reset Password
                </button>
            </form>

            <!-- Back to Login -->
            <div class="text-center mt-3">
                <a href="{{ route('admin.login') }}" class="text-decoration-none">
                    <i class="ri-arrow-left-line"></i> Back to Login
                </a>
            </div>

        </div>
    </div>

    <!-- Scripts -->
    <script>
        function disableBtn() {
            let btn = document.getElementById('resetBtn');
            btn.disabled = true;
            btn.innerHTML = "<i class='ri-loader-line ri-spin me-1'></i> Resetting...";
        }

        function togglePassword(fieldId, iconSpan) {
            const field = document.getElementById(fieldId);
            const icon = iconSpan.querySelector('i');
            if (field.type === "password") {
                field.type = "text";
                icon.classList.remove('ri-eye-line');
                icon.classList.add('ri-eye-off-line');
            } else {
                field.type = "password";
                icon.classList.remove('ri-eye-off-line');
                icon.classList.add('ri-eye-line');
            }
        }
    </script>

</body>
</html>