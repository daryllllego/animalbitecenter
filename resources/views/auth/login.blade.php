<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cebu Animal Bite Clinic - Login</title>
    <link href="{{ asset('css/branding-override.css') }}" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/cebuABC.png') }}?v=1">
    <link rel="apple-touch-icon" href="{{ asset('images/cebuABC.png') }}?v=1">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Line Awesome Icons -->
    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
    <div class="login-wrapper">
        <div class="decorative-background">
            <div class="circle circle-1" style="background: rgba(41, 83, 232, 0.1);"></div>
            <div class="circle circle-2" style="background: rgba(41, 83, 232, 0.05);"></div>
            <div class="circle circle-3" style="background: rgba(41, 83, 232, 0.08);"></div>
        </div>

        <div class="login-card">
            <!-- Left Panel - Logo and Branding -->
            <div class="card-left" style="background: linear-gradient(135deg, #2953e8 0%, #4f73ff 100%);">
                <div class="logo-wrapper">
                    <img src="{{ asset('images/cebuABC.png') }}" alt="Cebu Animal Bite Clinic Logo" class="logo"
                        style="max-width: 350px;">
                </div>
                <h1 class="brand-title">Cebu ABC</h1>
                <p class="brand-subtitle">Animal Bite Center Management</p>
            </div>

            <!-- Right Panel - Login Form -->
            <div class="card-right">
                @if(session('error'))
                    <div class="alert alert-error">
                        <i class="las la-exclamation-circle"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="login-form">
                    @csrf

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <div class="input-wrapper">
                            <i class="las la-envelope"></i>
                            <input type="text" id="email" name="email" placeholder="name@example.com"
                                value="{{ old('email') }}" required autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-wrapper">
                            <i class="las la-lock"></i>
                            <input type="password" id="password" name="password" placeholder="••••••••" required>
                            <button type="button" class="toggle-password" onclick="togglePassword()">
                                <i class="las la-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-login" style="background: #2953e8;">
                        <span>Sign In</span>
                        <i class="las la-arrow-right"></i>
                    </button>
                </form>

                <div class="form-footer">
                    <p>&copy; {{ date('Y') }} Cebu Animal Bite Clinic. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('la-eye');
                toggleIcon.classList.add('la-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('la-eye-slash');
                toggleIcon.classList.add('la-eye');
            }
        }

        // Add smooth entrance animation
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelector('.login-card').classList.add('loaded');
        });
    </script>
</body>

</html>