<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Enter OTP - HomeSena</title>

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
        .otp-card {
            width: 100%;
            max-width: 420px;
            margin: -70px auto 0;
            border-radius: 14px;
        }

        .btn-verify {
            background: linear-gradient(135deg, #5f2c82);
            border: none;
            border-radius: 8px;
            font-weight: 500;
        }

        .btn-verify:hover {
            opacity: 0.9;
        }

        /* OTP Input Boxes */
        .otp-inputs {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .otp-inputs input {
            width: 45px;
            height: 50px;
            text-align: center;
            font-size: 18px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .otp-inputs input:focus {
            border-color: #5f2c82;
            box-shadow: none;
            outline: none;
        }

        /* Mobile */
        @media (max-width: 768px) {
            .otp-card {
                margin-top: -50px;
                padding: 0 10px;
            }

            .card-body {
                padding: 20px;
            }
        }

        @media (max-width: 480px) {
            .otp-inputs input {
                width: 40px;
                height: 45px;
                font-size: 16px;
            }
        }
    </style>
</head>

<body>

    <!-- Top -->
    <div class="top-section">
        <img src="{{ asset('assets/img/logo.svg') }}" alt="HomeSena Logo">
        <p class="mt-2">Enter the OTP sent to your email</p>
    </div>

    <!-- Card -->
    <div class="card shadow otp-card border-0">
        <div class="card-body p-4">

            <h4 class="text-center fw-bold mb-3">Enter OTP</h4>

            <!-- Messages -->
            @if(session('success'))
                <p class="text-success text-center">{{ session('success') }}</p>
            @endif

            @if(session('error'))
                <p class="text-danger text-center">{{ session('error') }}</p>
            @endif

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
            <form method="POST" action="{{ route('admin.verifyOtp') }}" onsubmit="disableBtn()">
                @csrf

                <input type="hidden" name="email" value="{{ session('email') ?? old('email') }}">

                <!-- OTP Inputs -->
                <div class="otp-inputs mb-3">
                    <input type="text" maxlength="1" oninput="moveNext(this, 0)">
                    <input type="text" maxlength="1" oninput="moveNext(this, 1)">
                    <input type="text" maxlength="1" oninput="moveNext(this, 2)">
                    <input type="text" maxlength="1" oninput="moveNext(this, 3)">
                    <input type="text" maxlength="1" oninput="moveNext(this, 4)">
                    <input type="text" maxlength="1" oninput="moveNext(this, 5)">
                </div>

                <!-- Hidden OTP field -->
                <input type="hidden" name="otp" id="otp">

                <!-- Button -->
                <button id="verifyBtn" type="submit" class="btn btn-verify w-100 py-2 text-white">
                    <i class="ri-check-line me-1"></i> Verify OTP
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

    <!-- Scripts -->
    <script>
        function moveNext(input, index) {
            let value = input.value;

            if (value.length === 1 && index < 5) {
                input.nextElementSibling.focus();
            }

            // Combine OTP
            let otp = '';
            document.querySelectorAll('.otp-inputs input').forEach(el => {
                otp += el.value;
            });

            document.getElementById('otp').value = otp;
        }

        function disableBtn() {
            let btn = document.getElementById('verifyBtn');
            btn.disabled = true;
            btn.innerHTML = "<i class='ri-loader-line ri-spin me-1'></i> Verifying...";
        }
    </script>

</body>
</html>