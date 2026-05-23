<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="icon" href="{{ asset('images/OL.ico') }}" sizes="16x16">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body>
    <div class="bg-box">
        <div class="d-flex justify-content-center align-items-center" style="padding:100px">
            <div class="container card text-dark glass p-4" style="max-width: 500px;">
                <div>
                    <h1 class="text-center fs-1 fw-bold mb-5 mt-4">
                        Login
                    </h1>
                </div>
                <form class="form" id="loginForm">
                    @csrf
                    <div class="container d-flex flex-column justify-content-center align-items-center p-4">
                        <div class="container position-relative mb-3">
                            <label for="email" class="mb-1 fs-6">Email</label>
                            <span class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                    <path
                                        d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
                                </svg>
                            </span>
                            <x-form-input type="text" name="email" value="{{ old('email') }}"
                                placeholder="Type your email"></x-form-input>
                        </div>
                        <div class="w-100 ps-3 text-start">
                            <x-form-error name="email"></x-form-error>
                        </div>
                        <div class="container p-0 w-100 mb-3">
                            <div class="container position-relative mb-3">
                                <label for="password" class="mb-1 fs-6">Password</label>
                                <span class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-lock" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M8 0a4 4 0 0 1 4 4v2.05a2.5 2.5 0 0 1 2 2.45v5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5v-5a2.5 2.5 0 0 1 2-2.45V4a4 4 0 0 1 4-4M4.5 7A1.5 1.5 0 0 0 3 8.5v5A1.5 1.5 0 0 0 4.5 15h7a1.5 1.5 0 0 0 1.5-1.5v-5A1.5 1.5 0 0 0 11.5 7zM8 1a3 3 0 0 0-3 3v2h6V4a3 3 0 0 0-3-3" />
                                    </svg>
                                </span>
                                <x-form-input type="password" name="password" value="{{ old('email') }}"
                                    placeholder="Type your password"></x-form-input>
                            </div>
                            <div class="w-100 ps-3 text-start">
                                <x-form-error name="password"></x-form-error>
                            </div>
                            <div class="container d-flex w-100">
                                <input name="remember_me" type="checkbox" value="true" class="form-check-input me-2">
                                <p class="fw-light">Remember Me</p>
                                <a href="{{ route('password.forget') }}" class="link_light ms-auto">Forget password?
                                </a>
                            </div>
                        </div>
                        <div class="container text-center">
                            <x-form-button class="w-100">
                                Login
                            </x-form-button>
                            <div class="d-flex gap-3 align-items-center mt-3">
                                <hr class="w-50">
                                <p class="m-0">Or</p>
                                <hr class="w-50">
                            </div>
                            <a href="{{ route('auth.redirect') }}"
                                class="o-auth d-flex gap-3 text-decoration-none fw-light w-100 py-2 mt-3 border border-1 rounded-3 justify-content-center align-items-center">
                                <img src="{{ asset('images/search.png') }}" alt="" width="30"
                                    height="30">
                                Log In With Google
                            </a>
                            <div class="mt-4">
                                Don't have an account ?
                                <a href="{{ route('register') }}" class="link_light"> SignUp</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
