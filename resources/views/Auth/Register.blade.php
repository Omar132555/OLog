<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="icon" href="{{ asset('images/OL.ico') }}" sizes="16x16">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body>
    <div class="bg-box">
        <div class="d-flex justify-content-center align-items-center w-100 h-100" style="padding:100px">
            <div class="container card text-dark glass p-3" style="max-width: 520px;">
                <div>
                    <h1 class="text-center fs-1 fw-bold mb-2 mt-4">
                        Register
                    </h1>
                </div>
                <form id="registerForm" class="form">
                    @csrf
                    <div class="container d-flex flex-column justify-content-center align-items-center p-5">
                        <div class="container position-relative mb-3">
                            <label for="name" class="mb-1 fs-6">Name</label>
                            <span class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                    <path
                                        d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
                                </svg>
                            </span>
                            <x-form-input type="text" name="name" value="{{ old('name') }}"
                                placeholder="Type your name"></x-form-input>
                        </div>
                        <x-form-error name="name"></x-form-error>
                        <div class="container position-relative mb-3">
                            <label for="email" class="mb-1 fs-6">Email</label>
                            <span class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
                                    <path
                                        d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z" />
                                </svg>
                            </span>
                            <x-form-input type="email" name="email" value="{{ old('email') }}"
                                placeholder="Type your email"></x-form-input>
                        </div>
                        <x-form-error name="email"></x-form-error>
                        <div class="container position-relative mb-3">
                            <label for="password" class="mb-1 fs-6">Password</label>
                            <span class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-lock" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M8 0a4 4 0 0 1 4 4v2.05a2.5 2.5 0 0 1 2 2.45v5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5v-5a2.5 2.5 0 0 1 2-2.45V4a4 4 0 0 1 4-4M4.5 7A1.5 1.5 0 0 0 3 8.5v5A1.5 1.5 0 0 0 4.5 15h7a1.5 1.5 0 0 0 1.5-1.5v-5A1.5 1.5 0 0 0 11.5 7zM8 1a3 3 0 0 0-3 3v2h6V4a3 3 0 0 0-3-3" />
                                </svg>
                            </span>
                            <x-form-input type="password" value="{{ old('password') }}" name="password"
                                placeholder="Type your password"></x-form-input>
                        </div>
                        <x-form-error name="password"></x-form-error>
                        <div class="container p-0 w-100 mb-3">
                            <div class="container position-relative mb-3">
                                <label for="password_confirmation" class="mb-1 fs-6">Confirm Password</label>
                                <span class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-shield-lock" viewBox="0 0 16 16">
                                        <path
                                            d="M5.338 1.59a61 61 0 0 0-2.837.856.48.48 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.7 10.7 0 0 0 2.287 2.233c.346.244.652.42.893.533q.18.085.293.118a1 1 0 0 0 .101.025 1 1 0 0 0 .1-.025q.114-.034.294-.118c.24-.113.547-.29.893-.533a10.7 10.7 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.8 11.8 0 0 1-2.517 2.453 7 7 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7 7 0 0 1-1.048-.625 11.8 11.8 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 63 63 0 0 1 5.072.56" />
                                        <path
                                            d="M9.5 6.5a1.5 1.5 0 0 1-1 1.415l.385 1.99a.5.5 0 0 1-.491.595h-.788a.5.5 0 0 1-.49-.595l.384-1.99a1.5 1.5 0 1 1 2-1.415" />
                                    </svg>
                                </span>
                                <x-form-input type="password" value="{{ old('password_confirmation') }}"
                                    name="password_confirmation" placeholder="Confirm your password"></x-form-input>
                            </div>
                            <x-form-error name="password_confirmation"></x-form-error>
                            <div class="container d-flex w-100">
                                <input name="remember_me" type="checkbox" value="true" class="form-check-input me-2">
                                <p class="fw-light">Remember Me</p>
                                <a href="{{ route('login') }}" class="link_light ms-auto">Already Registered?</a>
                            </div>
                        </div>
                        <div class="container text-center">
                            <x-form-button class="w-100">
                                Sign Up
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
                                Sign Up With Google
                            </a>
                            <div class="mt-4">
                                Don't have an account ?
                                <a href="{{ route('posts.index') }}" class="link_light">Guest Mode</a>
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
