<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>HomeSena Forgot Password</title>
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
            height: 280px;
            color: white;
            text-align: center;
            padding-top: 40px;
            border-bottom-left-radius: 60% 20%;
            border-bottom-right-radius: 60% 20%;
        }

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
            color: #fff;
        }

        .btn-login:hover {
            opacity: 0.9;
        }
    </style>
</head>

<body>

    <!-- Top Section -->
    <div class="top-section">
        <h4>HomeSena</h4>
        <p class="mt-2">Enter your email to receive OTP</p>
    </div>

    <!-- Card -->
    <div class="card shadow login-card border-0">
        <div class="card-body p-4">

            <h4 class="text-center fw-bold mb-3">Forgot Password</h4>

            {{-- Success --}}
            @if (session('success'))
                <p class="text-success text-center">{{ session('success') }}</p>
            @endif

            {{-- Error --}}
            @if (session('error'))
                <p class="text-danger text-center">{{ session('error') }}</p>
            @endif

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger p-2">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li style="font-size: 14px;">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- FORM -->
            <form method="POST" action="{{ route('admin.sendOtp') }}" onsubmit="disableBtn()">
                @csrf

                <!-- Email -->
                <div class="mb-3">
                    <label>Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="ri-mail-line"></i>
                        </span>
                        <input 
                            type="email" 
                            name="email" 
                            class="form-control"
                            value="{{ old('email') }}"
                            placeholder="Enter your email" 
                            required>
                    </div>
                </div>

                <!-- Button -->
                <button id="otpBtn" type="submit" class="btn btn-login w-100 py-2">
                    <i class="ri-send-plane-line me-1"></i> Send OTP
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

    <!-- Disable button script -->
    <script>
        function disableBtn() {
            let btn = document.getElementById('otpBtn');
            btn.disabled = true;
            btn.innerHTML = "Sending...";
        }
    </script>

</body>
</html>