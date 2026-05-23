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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="icon" href="{{ asset('images/OL.ico') }}" sizes="16x16">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</head>

<body>
    <x-nav-bar></x-nav-bar>
    @yield('content')
    <script>
        const PUSHER_APP_KEY = "{{ env('PUSHER_APP_KEY') }}";
        const PUSHER_APP_CLUSTER = "{{ env('PUSHER_APP_CLUSTER') }}";
        const USER_ID = "{{ auth()->id() }}";
    </script>
    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.0/dist/echo.iife.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    @auth
    <!-- Global Chat Toast -->
    <div class="toast-container position-fixed bottom-0 end-0 p-4" style="z-index: 1050">
        <div id="globalChatToast" class="toast align-items-center text-white bg-success border-0 shadow-lg rounded-4" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body fs-6 fw-medium d-flex align-items-center" id="globalChatToastBody" style="cursor: pointer;">
                    <i class="bi bi-chat-dots-fill fs-4 me-3"></i>
                    <span id="toastMessageText">New message received!</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-3 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.Echo && USER_ID) {
                window.Echo.private(`chat.${USER_ID}`)
                    .listen('MessageSent', (e) => {
                        const message = e.message;
                        const senderName = message.sender ? message.sender.name : 'Someone';
                        
                        // Fire a custom event so the chat page can intercept it and update its UI
                        const event = new CustomEvent('globalMessageReceived', { detail: message });
                        document.dispatchEvent(event);

                        // If not on the chat page, show the WhatsApp-style toast
                        if (!window.location.pathname.includes('/chat')) {
                            const toastEl = document.getElementById('globalChatToast');
                            const textSpan = document.getElementById('toastMessageText');
                            
                            textSpan.innerHTML = `<strong>${senderName}:</strong> ${message.message.substring(0, 30)}${message.message.length > 30 ? '...' : ''}`;
                            
                            const toastBody = document.getElementById('globalChatToastBody');
                            toastBody.onclick = function() {
                                window.location.href = `/chat?user_id=${message.sender_id}`;
                            };
                            
                            const toast = new bootstrap.Toast(toastEl, { delay: 6000 });
                            toast.show();
                        }
                    });
            }
        });
    </script>
    @endauth
</body>
<footer class="bg-dark text-light pt-5 pb-3">
    <div class="container">
        <div class="row g-5">

            <div class="col-md-4">
                <h5 class="display-1 fw-bold mb-3">OLog</h5>
                <p class="text-secondary small">
                    A modern blog platform built to share knowledge, ideas,
                    and experiences in web development and technology.
                </p>
            </div>

            <div class="col-md-2">
                <h5 class="fw-bold mb-3">Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ route('Home') }}" class="text-decoration-none text-secondary d-block mb-2 footer-link">Home</a>
                    </li>
                    <li><a href="{{ route('posts.index') }}" class="text-decoration-none text-secondary d-block mb-2 footer-link">Explore
                        </a></li>
                    <li><a href="{{ route('posts.category') }}"
                            class="text-decoration-none text-secondary d-block mb-2 footer-link">Categories</a></li>
                    <li><a href="{{ route('Home') }}" class="text-decoration-none text-secondary footer-link">About</a></li>
                </ul>
            </div>

            <div class="col-md-3">
                <h5 class="fw-bold mb-3">Categories</h5>
                <ul class="list-unstyled">
                    @foreach ($categories as $category)
                        <li><a href="{{ route('posts.category') }}?category={{ $category->id }}"
                                class="text-decoration-none text-secondary d-block mb-2 footer-link">{{ $category->name }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <h5 class="fw-bold mb-3">Contact Info</h5>
                    <div class="col-md-3">
                        <p class="text-secondary small mb-2">Cairo,&nbspEgypt</p>
                        <p class="text-secondary small mb-2">omar.mostafa1325@gmail.com</p>
                        <p class="text-secondary small">+20&nbsp10&nbsp308&nbsp16322</p>
                    </div>
                </div>
            </div>
        </div>
        <hr class="border-secondary my-5">
        <div class="text-center small text-secondary">
            © 2026 OLog - Built By Eng.Omar Mostafa
        </div>
    </div>
</footer>

</html>
