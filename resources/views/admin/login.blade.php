<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>HomeSena Admin Login</title>

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
            min-height: 220px;
            color: #5f2c82;
            text-align: center;
            padding: 30px 15px;
            border-bottom-left-radius: 60% 20%;
            border-bottom-right-radius: 60% 20%;
        }

        .top-section img {
            max-width: 150px;
            height: auto;
        }

        /* Login Card */
        .login-card {
            width: 100%;
            max-width: 420px;
            border-radius: 14px;
            margin: -80px auto 0;
            z-index: 10;
        }

        .form-control {
            height: 45px;
            border-radius: 8px;
        }

        .btn-login {
            background: linear-gradient(135deg, #5f2c82);
            border: none;
            border-radius: 8px;
            font-weight: 500;
        }

        .btn-login:hover {
            opacity: 0.9;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .top-section {
                min-height: 180px;
                padding: 20px 10px;
            }

            .login-card {
                margin-top: -60px;
                padding: 0 10px;
            }

            .card-body {
                padding: 20px;
            }
        }

        @media (max-width: 480px) {
            .login-card {
                margin-top: -50px;
            }

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

    <!-- Top Section -->
    <div class="top-section">
        <img src="{{ asset('assets/img/logo.svg') }}" alt="HomeSena Logo">
        <p class="mt-2">Welcome back! Access your admin dashboard easily.</p>
    </div>

    <!-- Login Card -->
    <div class="card shadow login-card border-0">
        <div class="card-body p-4">

            <h4 class="text-center fw-bold mb-3">Admin Login</h4>

            @if (session('error'))
                <p class="text-danger text-center">
                    {{ session('error') }}
                </p>
            @endif

            <form method="POST" action="/admin/login">
                @csrf

                <!-- Email -->
                <div class="mb-3">
                    <label>Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="ri-mail-line"></i>
                        </span>
                        <input type="text" 
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="Enter your email" 
                               name="email" 
                               value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label>Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="ri-lock-line"></i>
                        </span>
                        <input type="password" 
                               id="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Enter your password" 
                               name="password">

                        <span class="input-group-text bg-white" style="cursor:pointer;" onclick="togglePassword()">
                            <i class="ri-eye-line" id="eyeIcon"></i>
                        </span>

                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Options -->
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <input type="checkbox"> Remember me
                    </div>
                    <a href="{{ route('admin.forgot') }}" class="text-decoration-none">
                        Forgot password?
                    </a>
                </div>

                <!-- Button -->
                <button class="btn btn-login w-100 text-white py-2">
                    Login
                </button>

            </form>

        </div>
    </div>

    <!-- Script -->
    <script>
        function togglePassword() {
            let password = document.getElementById("password");
            let icon = document.getElementById("eyeIcon");

            if (password.type === "password") {
                password.type = "text";
                icon.classList.remove("ri-eye-line");
                icon.classList.add("ri-eye-off-line");
            } else {
                password.type = "password";
                icon.classList.remove("ri-eye-off-line");
                icon.classList.add("ri-eye-line");
            }
        }
    </script>

</body>
</html>