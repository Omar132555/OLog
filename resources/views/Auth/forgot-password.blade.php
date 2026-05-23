<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="icon" href="{{ asset('images/OL.ico') }}" sizes="16x16">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="icon" href="{{ asset('images/blog.png') }}" type="image/png">
    <style>
        body {
            background: linear-gradient(135deg, #005cb2, #33b0ea);
            height: 100vh;
        }

        .glass-card {
            backdrop-filter: blur(15px);
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: fadeUp 0.6s ease;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-control {
            border-radius: 12px;
        }

        .link{
            padding: 12px 105px;
            border-radius: 5px;
            transition: 0.3s ease;
        }

        .link:hover{
            background-color: rgba(255, 255, 255, 0.179);
        }
    </style>

<body>
    <div class="d-flex justify-content-center align-items-center vh-100">

        <form data-url="{{ route('password.email') }}" id="pass_update" class="glass-card p-5 text-white"
            style="width: 400px;">

            <h3 class="text-center mb-5 fw-bold">Forgot Password</h3>

            <div class="mb-4">
                <label class="form-label">Email</label>
                <input name="email" type="email" class="form-control"
                    placeholder="example@email.com">
            </div>
            <x-form-error name="email"></x-form-error>

            <x-form-button class="w-100">Send Reset Link</x-form-button>

            <div class="text-center mt-4 w-100">
                <a href="{{ route('login') }}" class="link text-white text-decoration-none small w-100">
                    Back to login
                </a>
            </div>

        </form>

    </div>
    
    <script src="{{ asset('js/app.js') }}"></script>
</body>
