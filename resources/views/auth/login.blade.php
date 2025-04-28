<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to Account</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <!-- Abstract splash elements -->
    <div class="splash splash-1"></div>
    <div class="splash splash-2"></div>
    <div class="splash splash-3"></div>

    <div class="login-container">
        <img src="assets/Logo universitas.png" alt="University of Adelaide Logo" class="logo">
        <h1>Login to Account</h1>
        <p class="description">Please enter your username and password to continue</p>

        @if ($errors->any())
            <div class="alert-error">
                @foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login.process') }}">
            @csrf
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="1.23.45.6.78" value="{{ old('username') }}">
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
            </div>

            <div class="account-options">
                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember" checked>
                    <label for="remember">Remember Password</label>
                </div>
                <div class="forgotten-password">
                    <a href="#">Forgot Password?</a>
                </div>
            </div>

            <button type="submit">Sign In</button>
        </form>

        <div class="create-account">
            Don't have an account? <a href="#">Create Account</a>
        </div>
    </div>

    <div class="footer">
        Copyright © 2025 Polytechnic State Semarang
    </div>
</body>
</html>