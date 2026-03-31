<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password - HomeSena</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: sans-serif;
            background: #5f2c82;
        }

        /* Top Section */
        .top-section {
            background: white;
            min-height: 200px;
            color: #5f2c82;
            text-align: center;
            padding: 30px 15px;
            border-bottom-left-radius: 60% 20%;
            border-bottom-right-radius: 60% 20%;
        }

        .top-section img {
            max-width: 150px;
        }

        /* Card */
        .reset-card {
            width: 100%;
            max-width: 420px;
            margin: -70px auto 0;
            border-radius: 14px;
        }

        .form-control {
            height: 45px;
            border-radius: 8px;
        }

        .input-group-text {
            cursor: pointer;
        }

        .btn-reset {
            background: linear-gradient(135deg, #5f2c82);
            border: none;
            border-radius: 8px;
            font-weight: 500;
        }

        .btn-reset:hover {
            opacity: 0.9;
        }

        /* Mobile */
        @media (max-width: 768px) {
            .reset-card {
                margin-top: -50px;
                padding: 0 10px;
            }

            .card-body {
                padding: 20px;
            }
        }

        @media (max-width: 480px) {
            .top-section img {
                max-width: 120px;
            }

            h4 {
                font-size: 18px;
            }
        }
    </style>
</head>

<body>

    <!-- Top -->
    <div class="top-section">
        <img src="{{ asset('assets/img/logo.svg') }}" alt="HomeSena Logo">
        <p class="mt-2">Set your new password securely</p>
    </div>

    <!-- Card -->
    <div class="card shadow reset-card border-0">
        <div class="card-body p-4">

            <h4 class="text-center fw-bold mb-3">Reset Password</h4>

            <!-- Session Error -->
            @if(session('error'))
                <p class="text-danger text-center">{{ session('error') }}</p>
            @endif

            <!-- Validation -->
            @if ($errors->any())
                <div class="alert alert-danger p-2">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li style="font-size: 14px;">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('admin.resetPasswordSubmit') }}" onsubmit="disableBtn()">
                @csrf

                <input type="hidden" name="email" value="{{ session('email') }}">

                <!-- New Password -->
                <div class="mb-3">
                    <label>New Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="ri-lock-2-line"></i>
                        </span>
                        <input type="password" id="password" name="password" 
                               class="form-control" placeholder="Enter new password" required>
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
                        <input type="password" id="confirm_password" name="password_confirmation" 
                               class="form-control" placeholder="Confirm new password" required>
                        <span class="input-group-text bg-white" onclick="togglePassword('confirm_password', this)">
                            <i class="ri-eye-line"></i>
                        </span>
                    </div>
                </div>

                <!-- Button -->
                <button id="resetBtn" type="submit" class="btn btn-reset w-100 py-2 text-white">
                    <i class="ri-refresh-line me-1"></i> Reset Password
                </button>

            </form>

            <!-- Back -->
            <div class="text-center mt-3">
                <a href="{{ route('admin.login') }}" class="text-decoration-none">
                    <i class="ri-arrow-left-line"></i> Back to Login
                </a>
            </div>

        </div>
    </div>

    <!-- Script -->
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