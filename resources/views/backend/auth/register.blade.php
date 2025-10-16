<!DOCTYPE html>
<html lang="en">
<head>
    @include('backend.includes.head')
</head>
<body>
<div class="bg-body-tertiary min-vh-100 d-flex flex-row align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mb-4 mx-4">
                    <div class="card-body p-4">
                        <h1>Register</h1>
                        <p class="text-body-secondary">Create your account</p>

{{--                        <form method="POST" action="{{ route('register') }}">--}}
                        <form method="POST" action="#">
                        @csrf

                        <!-- Name -->
                            <div class="input-group mb-3">
                                <span class="input-group-text">
                                    <i class="fa-solid fa-user"></i>
                                </span>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                       name="name" value="{{ old('name') }}" required autofocus placeholder="Name">
                            </div>
                            @error('name')
                            <div class="text-danger mb-3">{{ $message }}</div>
                            @enderror

                        <!-- Email -->
                            <div class="input-group mb-3">
                                <span class="input-group-text">
                                    <i class="fa-solid fa-envelope"></i>
                                </span>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                       name="email" value="{{ old('email') }}" required placeholder="Email">
                            </div>
                            @error('email')
                            <div class="text-danger mb-3">{{ $message }}</div>
                            @enderror

                        <!-- Password -->
                            <div class="input-group mb-3">
                                <span class="input-group-text">
                                    <i class="fa-solid fa-lock"></i>
                                </span>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                       name="password" required placeholder="Password">
                            </div>
                            @error('password')
                            <div class="text-danger mb-3">{{ $message }}</div>
                            @enderror

                        <!-- Confirm Password -->
                            <div class="input-group mb-4">
                                <span class="input-group-text">
                                    <i class="fa-solid fa-lock"></i>
                                </span>
                                <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                       name="password_confirmation" required placeholder="Repeat password">
                            </div>
                            @error('password_confirmation')
                            <div class="text-danger mb-3">{{ $message }}</div>
                            @enderror

                            <button class="btn btn-block btn-success" type="submit">Create Account</button>

                            <div class="mt-3 text-center">
                                <a href="{{ route('admin.login') }}" class="text-decoration-none">
                                    Already registered? Login
                                </a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
