<!DOCTYPE html>
<!--
* CoreUI - Free Bootstrap Admin Template
* @version v5.2.0
* @link https://coreui.io/product/free-bootstrap-admin-template/
* Copyright (c) 2025 creativeLabs Łukasz Holeczek
* Licensed under MIT (https://github.com/coreui/coreui-free-bootstrap-admin-template/blob/main/LICENSE)
-->

<html lang="en">
<head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <title>CoreUI Free Bootstrap Admin Template</title>
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="assets/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!-- Vendors styles-->
    <link rel="stylesheet" href="node_modules/simplebar/dist/simplebar.css">
    <link rel="stylesheet" href="css/vendors/simplebar.css">
    <!-- Main styles for this application-->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
<div class="bg-body-tertiary min-vh-100 d-flex flex-row align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card-group d-block d-md-flex row">
                    <div class="card col-md-7 p-4 mb-0">
                        <div class="card-body">
                            <h1>Login</h1>
                            <p class="text-body-secondary">Sign In to your account</p>

                            <form method="POST" action="{{ route('admin.login') }}">
                            @csrf

                            <!-- Email Address -->
                                <div class="input-group mb-3">
                <span class="input-group-text">
                    <i class="fa-solid fa-user"></i>
                </span>
                                    <input id="email" class="form-control @error('email') is-invalid @enderror"
                                           type="email" name="email" value="{{ old('email') }}"
                                           required autofocus placeholder="Email">
                                </div>
                                @error('email')
                                <div class="text-danger mb-3">{{ $message }}</div>
                                @enderror

                            <!-- Password -->
                                <div class="input-group mb-4">
                <span class="input-group-text">
                    <i class="fa-solid fa-lock"></i>
                </span>
                                    <input id="password" class="form-control @error('password') is-invalid @enderror"
                                           type="password" name="password" required placeholder="Password">
                                </div>
                                @error('password')
                                <div class="text-danger mb-3">{{ $message }}</div>
                            @enderror

                            <!-- Remember Me -->
                                <div class="form-check mb-4">
                                    <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                                    <label for="remember_me" class="form-check-label">{{ __('Remember me') }}</label>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary px-4">{{ __('Log in') }}</button>
                                    </div>
                                    <div class="col-6 text-end">
                                        @if (Route::has('password.request'))
                                            <a class="btn btn-link px-0" href="{{ route('password.request') }}">
                                                {{ __('Forgot password?') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card col-md-5 text-white bg-primary py-5">
                        <div class="card-body text-center">
                            <div>
                                <h2>Sign up</h2>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                    incididunt ut labore et dolore magna aliqua.</p>
                                <button class="btn btn-lg btn-outline-light mt-3" type="button">Register Now!</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
