<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>HomeSena Admin Login</title>

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: sans-serif;
            background: #f4f4f4;
        }

        /* Top Gradient Section */
        .top-section {
            background: linear-gradient(135deg, #7b2ff7, #5f2c82);
            height: 280px;
            position: relative;
            color: white;
            text-align: center;
            padding-top: 40px;
            border-bottom-left-radius: 60% 20%;
            border-bottom-right-radius: 60% 20%;
        }

        .top-section h1 {
            font-weight: bold;
        }

        /* Login Card */
        .login-card {
            width: 420px;
            border-radius: 14px;
            margin: -100px auto 0;
            position: relative;
            z-index: 10;
        }

        .form-control {
            height: 45px;
            border-radius: 8px;
        }

        .btn-login {
            background: linear-gradient(135deg, #7b2ff7, #5f2c82);
            border: none;
            border-radius: 8px;
            font-weight: 500;
        }

        .btn-login:hover {
            opacity: 0.9;
        }

        /* Right Character (optional image) */
        .character {
            position: absolute;
            right: 15%;
            bottom: 0;
            height: 300px;
        }

        @media(max-width: 768px) {
            .character {
                display: none;
            }
        }
    </style>

</head>

<body>

    <!-- Top Section -->
    <div class="top-section">
        <h4>HomeSena</h4>
        <p class="mt-2">Welcome back! Access your admin dashboard easily.</p>
    </div>

    <!-- Login Card -->
    <div class="card shadow login-card border-0">
        <div class="card-body p-4">

            <h4 class="text-center fw-bold mb-3">Admin Login</h4>

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
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
                        <input type="text" class="form-control @error('email') is-invalid @enderror"
                            placeholder="Enter your email" name="email" value="{{ old('email') }}">
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
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            value="{{ old('password') }}" placeholder="Enter your password" name="password">
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <span class="input-group-text bg-white">
                            <i class="ri-eye-line"></i>
                        </span>
                    </div>
                </div>

                <!-- Options -->
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <input type="checkbox"> Remember me
                    </div>
                    <a href="#" class="text-decoration-none">Forgot password?</a>
                </div>

                <!-- Button -->
                <button class="btn btn-login w-100 text-white py-2">
                    Login
                </button>

            </form>

        </div>
    </div>
</body>

</html>