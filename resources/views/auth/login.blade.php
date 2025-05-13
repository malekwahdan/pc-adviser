<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pc-Adviser - Sign In</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .card {
            max-width: 450px;
            width: 100%;
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }
        .form-container {
            padding: 2rem;
        }
        .icon-container {
            text-align: center;
            margin-bottom: 1rem;
        }
        .icon-container i {
            font-size: 2rem;
            color: #212529;
        }
        .form-control {
            padding: 0.75rem 1rem;
        }
        .signin-btn {
            padding: 0.75rem 1rem;
            background-color: #0f172a;
            border: none;
        }
        .signin-btn:hover {
            background-color: #1e293b;
        }
        .form-title {
            text-align: center;
            margin-bottom: 0.5rem;
        }
        .form-subtitle {
            text-align: center;
            color: #6c757d;
            margin-bottom: 1.5rem;
        }
        .bottom-text {
            text-align: center;
            margin-top: 1.5rem;
        }
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        .input-group-text {
            background-color: transparent;
            border-right: none;
        }
        .input-group .form-control {
            border-left: none;
        }
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .form-check-label {
            color: #6c757d;
        }
        .forgot-link {
            color: #6c757d;
            text-decoration: none;
        }
        .forgot-link:hover {
            text-decoration: underline;
        }
        .alert {
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <!-- Login Form -->
    <div class="card">
        <div class="form-container">
            <div class="icon-container">
                <i class="bi bi-laptop"></i>
            </div>
            <h3 class="form-title">Welcome Back</h3>
            <p class="form-subtitle">Sign in to continue to your account</p>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('user.login') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required autofocus>
                    </div>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="remember-forgot">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember_me" name="remember_me" {{ old('remember_me') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember_me">
                            Remember me
                        </label>
                    </div>
                    <a href="" class="forgot-link">Forgot password?</a>
                </div>

                <button type="submit" class="btn btn-primary w-100 signin-btn">Sign in</button>
            </form>

            <div class="bottom-text">
                Don't have an account? <a href="{{ route('register') }}">Sign up</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
