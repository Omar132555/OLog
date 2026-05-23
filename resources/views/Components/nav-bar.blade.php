@php
    use Carbon\Carbon;
    $user = auth()->user();
    //dd($user->notifications);
@endphp
<nav class="navbar p-3 navbar-expand-lg sticky-top bg-white">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">OLog</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 ms-2 fw-semibold gap-3 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('Home') ? 'active-link active' : 'inactive-link' }} my-link"
                        aria-current="page" href="{{ route('Home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('posts.index') ? 'active-link active' : 'inactive-link' }} my-link"
                        aria-current="page" href="{{ route('posts.index') }}">Explore</a>
                </li>
                @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.feed') ? 'active-link active' : 'inactive-link' }} my-link"
                            aria-current="page" href="{{ route('user.feed') }}">Feed</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('chat.index') ? 'active-link active' : 'inactive-link' }} my-link"
                            aria-current="page" href="{{ route('chat.index') }}">Chats</a>
                    </li>
                @endauth
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('') ? 'active-link active' : 'inactive-link' }} my-link"
                        aria-current="page" href="{{ route('posts.category') }}">Categories</a>
                </li>
            </ul>
            <div class="d-flex search-container justify-content-center w-100 align-items-center me-auto">
                <form action="" class="m-0 position-relative w-75">
                    <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3"></i>
                    <input type="text" class="border border-dark text-center rounded-4 w-100 p-1" placeholder="blog"
                        id="searchBar" url="{{ route('posts.search') }}"
                        usersRoute="{{ route('user.profile', ':userId') }}"
                        postsRoute="{{ route('posts.show', ':postId') }}">
                    <div id="resultsBar" class="position-absolute w-100 z-3 rounded-4 bg-light">
                    </div>
                </form>
            </div>

        </div>
        @guest
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('login') }}" class="btn p-2 ps-4 pe-4 auth-btn2">Login</a>
                <a href="{{ route('register') }}" class="btn btn-outline-dark p-2 ps-3 pe-3 auth-btn">Sign Up</a>
            </div>
        @endguest
        @auth
            <div class="createPost me-5">
                <a href="{{ route('posts.create') }}">
                    Create Post
                </a>
                <i class="bi bi-plus fs-5"></i>
            </div>
            <div class="dropdown pe-5">
                <button class="btn p-0 border-0 bg-transparent d-flex align-items-center" id="bell" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-bell fs-5 notification position-relative">
                        @if ($user->unreadNotifications->count() != 0)
                            <div class="notification-counter">{{ $user->unreadNotifications->count() }}</div>
                        @else
                            <div class="notification-counter" style="display: none"></div>
                        @endif
                    </i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow" id="notification-container">
                    @if ($user->unreadNotifications->count() == 0)
                        <p class="text-muted px-4 m-0">Nothing New</p>
                    @endif
                    @foreach ($user->unreadNotifications->take(5) as $notification)
                        <li class="notification dropdown-item py-4">
                            <div class="d-flex gap-3 align-items-center">
                                @if ($notification->data['creatorImage'])
                                    <div class="avatar-circle">
                                        <img src="{{ asset('storage/' . $notification->data['creatorImage']) }}"
                                            class="rounded-circle flex-shrink-0" width="40" height="40" alt="">
                                    </div>
                                @else
                                    <div class="avatar-circle flex-shrink-0">
                                        {{ strtoupper(substr($notification->data['title'], 0, 2)) }}
                                    </div>
                                @endif
                                <div class="d-flex flex-column justify-content-start w-100">
                                    <div class="d-flex gap-4 align-items-center">
                                        <span class="fw-bold">
                                            <a href="{{ route('user.profile', $notification->data['userId']) }}?n={{ $notification->id }}"
                                                class="text-decoration-none">
                                                {{ $notification->data['title'] }}
                                            </a>
                                        </span>
                                        <span class="fw-semibold text-danger">{{ $notification->data['type'] }}</span>
                                        <p class="text-muted m-0">
                                            {{ Carbon::parse($notification->data['created_at'])->format('d M Y') }}
                                        </p>
                                        <button class="notificationDel border border-1 bg-transparent px-2 py-1 ms-auto"
                                            url="{{ route('notification.read', $notification->id) }}">
                                                Mark as Read
                                        </button>
                                    </div>
                                    <a href="{{ $notification->data['url'] }}?n={{ $notification->id }}"
                                        class="link-animation text-decoration-none">
                                        <div class="fw-light m-0">
                                            <span class="fw-normal">
                                                {{ $notification->data['messageType'] }}
                                            </span>
                                            {{ "\"" . $notification->data['message'] . "\"" }}
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="dropdown">
                <button class="btn p-0 border-0 bg-transparent d-flex align-items-center" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    @if ($user->image)
                        <div class="avatar-circle">
                            <img src="{{ asset('storage/' . $user->image) }}" class="rounded-circle" width="40"
                                height="40">
                        </div>
                    @else
                        <div class="avatar-circle">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li>
                        <a class="dropdown-item" href="{{ route('user.dashboard') }}">My Profile</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('user.dashboard') }}?section=posts">My Posts</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">Settings</a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item text-danger">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        @endauth
    </div>
</nav>
