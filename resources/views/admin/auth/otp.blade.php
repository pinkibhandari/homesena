<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Enter OTP - HomeSena</title>
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

        .otp-card {
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

        .btn-verify {
            background: linear-gradient(135deg, #7b2ff7, #5f2c82);
            border: none;
            border-radius: 8px;
            font-weight: 500;
            color: #fff;
        }

        .btn-verify:hover {
            opacity: 0.9;
        }
    </style>
</head>

<body>

    <!-- Top Section -->
    <div class="top-section">
        <h4>HomeSena</h4>
        <p class="mt-2">Enter the OTP sent to your email</p>
    </div>

    <!-- OTP Card -->
    <div class="card shadow otp-card border-0">
        <div class="card-body p-4">

            <h4 class="text-center fw-bold mb-3">Enter OTP</h4>

            {{-- Success Message --}}
            @if(session('success'))
                <p class="text-success text-center">{{ session('success') }}</p>
            @endif

            {{-- Error Message --}}
            @if(session('error'))
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

            <!-- OTP Form -->
            <form method="POST" action="{{ route('admin.verifyOtp') }}" onsubmit="disableBtn()">
                @csrf

                <!-- Hidden Email -->
                <input type="hidden" name="email" value="{{ session('email') ?? old('email') }}">

                <!-- OTP Input -->
                <div class="mb-3">
                    <label for="otp">OTP</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="ri-shield-keyhole-line"></i>
                        </span>
                        <input 
                            type="number" 
                            id="otp"
                            name="otp" 
                            class="form-control"
                            placeholder="Enter 6-digit OTP"
                            maxlength="6"
                            required>
                    </div>
                </div>

                <!-- Verify Button -->
                <button id="verifyBtn" type="submit" class="btn btn-verify w-100 py-2">
                    <i class="ri-check-line me-1"></i> Verify OTP
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
            let btn = document.getElementById('verifyBtn');
            btn.disabled = true;
            btn.innerHTML = "<i class='ri-loader-line ri-spin me-1'></i> Verifying...";
        }
    </script>

</body>
</html>