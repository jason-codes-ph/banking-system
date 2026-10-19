<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            min-height: 100vh;
        }
        .hero {
            padding-top: 100px;
        }
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        }
        .btn-primary {
            padding: 12px 30px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent py-3">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="#">🏦 Banking System</a>
            <div class="d-flex gap-2">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-light">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-light">Register</a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <div class="hero">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card p-5 text-center">
                        <h1 class="display-4 fw-bold mb-4">Welcome to Banking System</h1>
                        <p class="lead text-muted mb-5">Manage your accounts, track transactions, and monitor your finances — all in one secure place.</p>
                        
                        @guest
                            <div class="d-flex justify-content-center gap-3">
                                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Create Account</a>
                                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">Sign In</a>
                            </div>
                        @else
                            <div class="alert alert-success">
                                Welcome back, <strong>{{ auth()->user()->name }}</strong>!
                            </div>
                            <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-lg mt-2">Go to Dashboard</a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>