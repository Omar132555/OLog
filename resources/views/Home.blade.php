@php
    use Carbon\Carbon;
    use App\Models\Category;
    use Illuminate\Support\Str;
@endphp
@extends('layouts.app')
@section('title')
    {{ 'Home' }}
@endsection
@section('content')
    {{-- ===================== STYLES ===================== --}}
    <style>

    </style>

    {{-- ===================== DELETE FUNCTION ===================== --}}
    <script>
        function delCheck(id) {
            if (confirm('Do you want to delete this post?')) {
                fetch(`/posts/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                }).then(() => {
                    const el = document.getElementById(id);
                    if (el) {
                        el.style.transition = 'opacity .4s';
                        el.style.opacity = 0;
                        setTimeout(() => el.remove(), 400);
                    }
                });
            }
        }
    </script>

    {{-- ===================== HERO ===================== --}}
    <section class="hero">
        <div class="hero-noise"></div>
        <div class="hero-left">
            <p class="hero-eyebrow">Est. 2026 &nbsp;·&nbsp; Volume I</p>
            <h1 class="hero-headline">Writing <em><br> What </em> Matters</h1>
            <p class="hero-sub">The latest industry insights, interviews, emerging technologies and resources for curious
                minds.</p>
            <a href="{{ route('posts.index') }}" class="hero-cta">
                Browse All Articles
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                    <path d="M1 7h12M8 2l5 5-5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </a>
            <div class="hero-stats">
                <div class="hero-stat">
                    <span>{{ Category::all()->count() }}</span>
                    <span>Categories</span>
                </div>
                <div class="hero-stat">
                    <span>∞</span>
                    <span>Stories</span>
                </div>
                <div class="hero-stat">
                    <span>01</span>
                    <span>Newsletter</span>
                </div>
                <div class="hero-stat ms-auto me-5 mt-auto">
                    <span style="font-size:1.1rem;">{{ Carbon::parse($featured->published_at)->format('M Y') }}</span>
                    <span>Latest Issue</span>
                </div>
            </div>
        </div>
        <div class="hero-right">
            <div class="hero-issue">

            </div>
        </div>
    </section>

    {{-- ===================== MARQUEE ===================== --}}
    <div class="marquee-strip">
        <div class="marquee-inner">
            <span>Programming</span>
            <span>Design</span>
            <span>Business</span>
            <span>Technology</span>
            <span>Industry News</span>
            <span>Interviews</span>
            <span>Resources</span>
            <span>Programming</span>
            <span>Design</span>
            <span>Business</span>
            <span>Technology</span>
            <span>Industry News</span>
            <span>Interviews</span>
            <span>Resources</span>
        </div>
    </div>
    <section class="ideas-section">
        <div class="ideas-container reveal">

            <div class="ideas-content">
                <span class="ideas-tag">Creative Space</span>

                <h2>Share your ideas with the world</h2>

                <p>
                    Write articles, inspire readers, and express your thoughts through a modern publishing experience.
                </p>
                @auth 
                <a href="{{ route('posts.create') }}" class="btn-primary text-decoration-none">Start Writing</a>
                @endauth
            </div>

            <div class="ideas-cards">

                <div class="idea-card">
                    <h3>Write Freely</h3>
                    <p>Create beautiful posts with a clean editor.</p>
                </div>

                <div class="idea-card">
                    <h3>Grow Audience</h3>
                    <p>Reach more readers and build your community.</p>
                </div>

                <div class="idea-card">
                    <h3>Stay Inspired</h3>
                    <p>Explore fresh ideas from other creators.</p>
                </div>

            </div>

        </div>

        <div class="chat-feature-container reveal mx-5 p-5 mt-5">

            <div class="chat-image-side">
                <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1200&auto=format&fit=crop"
                    alt="chatting feature">
            </div>

            <div class="chat-text-side">
                <span class="feature-badge">Live Chat</span>

                <h1>Start real conversations</h1>

                <p>
                    Chat instantly with other users, exchange ideas, and build real connections through private messaging
                    and live discussions.
                </p>

                <div class="chat-feature-list">
                    <div class="feature-item">
                        <i class="bi bi-chat-dots"></i>
                        <span>Real-time messaging</span>
                    </div>

                    <div class="feature-item">
                        <i class="bi bi-emoji-smile"></i>
                        <span>Emoji support</span>
                    </div>

                    <div class="feature-item">
                        <i class="bi bi-people"></i>
                        <span>Connect with creators</span>
                    </div>
                </div>
                @auth
                <a href="{{ route('chat.index') }}" class="text-decoration-none text-light">
                    <button>Open Chats</button>
                </a>
                @endauth
            </div>

        </div>
    </section>

    <section class="feed-feature-section">

        <div class="feed-feature-container">

            <div class="feed-text-side">

                <span class="feed-badge">Community Feed</span>

                <h1>Discover what people are sharing</h1>

                <p>
                    Explore posts from creators and friends, react to ideas, and stay connected with everything happening in
                    your community.
                </p>

                <div class="feed-cards">

                    <div class="mini-card">
                        <i class="bi bi-heart-fill"></i>
                        <div>
                            <h3>Like Posts</h3>
                            <p>Support creators instantly</p>
                        </div>
                    </div>

                    <div class="mini-card">
                        <i class="bi bi-chat-left-text-fill"></i>
                        <div>
                            <h3>Join Discussions</h3>
                            <p>Comment and interact freely</p>
                        </div>
                    </div>

                    <div class="mini-card">
                        <i class="bi bi-lightning-fill"></i>
                        <div>
                            <h3>Real Activity</h3>
                            <p>Fresh content every moment</p>
                        </div>
                    </div>

                </div>
                @auth
                <a href="{{ route('user.feed') }}" class="text-decoration-none text-light">
                    <button>Explore Feed</button>
                </a>
                @endauth
            </div>

            <div class="feed-image-side">
                <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=1200&auto=format&fit=crop"
                    alt="feed feature">
            </div>

        </div>

    </section>
    {{-- ===================== CATEGORIES ===================== --}}
    <section class="categories-section">
        <div class="container">
            <div class="section-header reveal">
                <div>
                    <p class="section-label">Explore</p>
                    <h2 class="section-title serif">Categories</h2>
                </div>
                <a href="{{ route('posts.index') }}" class="section-link">View all posts →</a>
            </div>
            <div class="cat-grid reveal">
                <a href="{{ route('posts.category') }}?category=82" class="cat-card">
                    <img src="{{ asset('storage/posts/Programming.webp') }}" alt="Programming">
                    <div class="cat-overlay">
                        <span class="cat-number">01</span>
                        <span class="cat-name">Programming</span>
                    </div>
                    <div class="cat-arrow"><i class="bi bi-arrow-up-right"></i></div>
                </a>
                <a href="{{ route('posts.category') }}?category=81" class="cat-card">
                    <img src="{{ asset('storage/posts/Design.jpg') }}" alt="Design">
                    <div class="cat-overlay">
                        <span class="cat-number">02</span>
                        <span class="cat-name">Design</span>
                    </div>
                    <div class="cat-arrow"><i class="bi bi-arrow-up-right"></i></div>
                </a>
                <a href="{{ route('posts.category') }}?category=83" class="cat-card">
                    <img src="{{ asset('storage/posts/Business.webp') }}" alt="Business">
                    <div class="cat-overlay">
                        <span class="cat-number">03</span>
                        <span class="cat-name">Business</span>
                    </div>
                    <div class="cat-arrow"><i class="bi bi-arrow-up-right"></i></div>
                </a>
            </div>
        </div>
    </section>

    {{-- ===================== FEATURED POST ===================== --}}
    <section class="featured-section">
        <div class="container">
            <div class="section-header reveal">
                <div>
                    <p class="section-label">Editor's Pick</p>
                    <h2 class="section-title serif">Featured Post</h2>
                </div>
            </div>
            <div class="featured-grid reveal">
                <div class="featured-img-wrap">
                    <img src="{{ Str::startsWith($featured->image, ['http://', 'https://']) ? $featured->image : asset('storage/' . $featured->image) }}"
                        alt="{{ $featured->title }}">
                    <span class="featured-badge">Featured</span>
                </div>
                <div class="featured-content">
                    <div>
                        <p class="featured-meta">
                            {{ $featured->category->name }}
                            &nbsp;·&nbsp;
                            {{ Carbon::parse($featured->published_at)->format('d M Y') }}
                        </p>
                        <h3 class="featured-title">
                            <a href="{{ route('posts.show', $featured->id) }}">{{ $featured->title }}</a>
                        </h3>
                        <p class="featured-desc">{{ Str::limit($featured->description, 220) }}</p>
                    </div>
                    <div class="featured-footer">
                        <div class="author-info">
                            <div class="author-avatar">
                                {{ strtoupper(substr($featured->user_id ? $featured->user->name : 'U', 0, 1)) }}
                            </div>
                            <div>
                                <div class="author-name">{{ $featured->user_id ? $featured->user->name : 'Unknown' }}
                                </div>
                                <div class="author-date">{{ Carbon::parse($featured->published_at)->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('posts.show', $featured->id) }}" class="read-btn">
                            Read Article
                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                                <path d="M1 6h10M7 2l4 4-4 4" stroke="currentColor" stroke-width="1.4"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== LATEST POSTS ===================== --}}
    <section class="posts-section">
        <div class="container">
            <div class="section-header reveal">
                <div>
                    <p class="section-label">Fresh off the Press</p>
                    <h2 class="section-title serif">Latest Posts</h2>
                </div>
                <a href="{{ route('posts.index') }}" class="section-link">All posts →</a>
            </div>
            <div class="row g-4">
                @foreach ($latest as $post)
                    <div class="col-md-4 reveal" id="{{ $post->id }}">
                        <article class="post-card">
                            <div class="post-card-img">
                                <a href="{{ route('posts.show', $post->id) }}">
                                    <img src="{{ Str::startsWith($post->image, ['http://', 'https://']) ? $post->image : asset('storage/' . $post->image) }}"
                                        alt="{{ $post->title }}">
                                </a>
                            </div>
                            <div class="post-card-body">
                                <p class="post-card-meta">
                                    {{ $post->user->name }} &nbsp;·&nbsp;
                                    {{ Carbon::parse($post->published_at)->format('d M Y') }}
                                </p>
                                <h3 class="post-card-title">
                                    <a href="{{ route('posts.show', $post->id) }}">{{ $post->title }}</a>
                                </h3>
                                <p class="post-card-excerpt">{{ Str::limit($post->description, 110) }}</p>
                                <div class="post-card-footer">
                                    <span class="pill-tag">{{ Str::limit($post->category->name, 18) }}</span>
                                    <a href="{{ route('posts.show', $post->id) }}" class="post-card-arrow">
                                        <i class="bi bi-arrow-up-right"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== POPULAR POSTS ===================== --}}
    <section class="popular-section">
        <div class="container">
            <div class="section-header reveal">
                <div>
                    <p class="section-label">Most Read</p>
                    <h2 class="section-title serif">Popular Posts</h2>
                </div>
            </div>
            <div class="row g-5">
                {{-- Left column: ranked list --}}
                <div class="col-lg-6 reveal">
                    <div class="popular-list">
                        @foreach ($mostPopular as $i => $post)
                            <a href="{{ route('posts.show', $post->id) }}" class="popular-item">
                                <div class="popular-thumb">
                                    <img src="{{ Str::startsWith($post->image, ['http://', 'https://']) ? $post->image : asset('storage/' . $post->image) }}"
                                        alt="{{ $post->title }}">
                                </div>
                                <div>
                                    <div class="popular-num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</div>
                                    <div class="popular-title">{{ $post->title }}</div>
                                    <div class="popular-meta">
                                        {{ $post->user->name }} &nbsp;·&nbsp;
                                        {{ Carbon::parse($post->published_at)->format('d M Y') }}
                                        &nbsp;·&nbsp; {{ $post->category->name }}
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
                {{-- Right column: card grid --}}
                <div class="col-lg-6">
                    <div class="row g-4">
                        @foreach ($mostPopular->take(4) as $post)
                            <div class="col-6 reveal" id="{{ $post->id }}">
                                <article class="post-card">
                                    <div class="post-card-img">
                                        <a href="{{ route('posts.show', $post->id) }}">
                                            <img src="{{ Str::startsWith($post->image, ['http://', 'https://']) ? $post->image : asset('storage/' . $post->image) }}"
                                                alt="{{ $post->title }}">
                                        </a>
                                    </div>
                                    <div class="post-card-body">
                                        <p class="post-card-meta">
                                            {{ Carbon::parse($post->published_at)->format('d M Y') }}</p>
                                        <h3 class="post-card-title" style="font-size:.95rem;">
                                            <a
                                                href="{{ route('posts.show', $post->id) }}">{{ Str::limit($post->title, 55) }}</a>
                                        </h3>
                                        <div class="post-card-footer mt-auto"
                                            style="padding-top:.75rem; border-top:1px solid var(--border);">
                                            <span class="pill-tag"
                                                style="font-size:.55rem;">{{ Str::limit($post->category->name, 14) }}</span>
                                            <a href="{{ route('posts.show', $post->id) }}" class="post-card-arrow"
                                                style="width:26px;height:26px;font-size:.75rem;">
                                                <i class="bi bi-arrow-up-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- ===================== SCROLL REVEAL SCRIPT ===================== --}}
    <script>
        (function() {
            const observer = new IntersectionObserver(entries => {
                entries.forEach(e => {
                    if (e.isIntersecting) {
                        e.target.classList.add('visible');
                        observer.unobserve(e.target);
                    }
                });
            }, {
                threshold: 0.1
            });

            document.querySelectorAll('.reveal').forEach((el, i) => {
                el.style.transitionDelay = (i % 4) * 0.08 + 's';
                observer.observe(el);
            });
        })();
    </script>
@endsection



{{-- 
@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
@endphp
@extends('layouts.app')
@section('title')
    {{ 'Home' }}
@endsection
@section('content')
    <script>
        function delCheck(id) {
            if (confirm('Do you want to delete this post?')) {
                fetch(`/posts/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': {{ csrf_token() }},
                        'Accept': 'application/json'
                    }
                }).then(response => {
                    document.getElementById(id).remove();
                });
            }
        }
    </script>
    <div>
        <div class="d-flex text-center justify-content-center align-items-center mb-5 hero-gradient position-relative" style="padding: 8rem 2rem; overflow: hidden; border-radius: 0 0 50px 50px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="glass-panel p-5 mx-auto" style="max-width: 800px; z-index: 2;">
                <h1 class="display-2 fw-bold text-white mb-3" style="text-shadow: 0 2px 10px rgba(0,0,0,0.2);">Writing What Matters</h1>
                <p class="fs-4 fw-light text-white mb-5" style="text-shadow: 0 1px 5px rgba(0,0,0,0.2);">The latest industry news, interviews, technologies and resources</p>
                <a href="{{ route('posts.index') }}" class="text-decoration-none">
                    <button class="modern-btn fs-5">
                        Browse Articles <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </a>
            </div>
        </div>

        <!-- Categories -->
        <div class="categories-section d-flex align-items-center container position-relative my-5">
            <img src="{{ asset('images/pencil.png') }}" class="pencil-animate" width="420px" height="420px" alt="pencil">
            <div class="text-animate ms-auto">
                <h1 class="display-3 fw-semibold text-end">Write. Share. Inspire.</h1>
                <p class=" text-end mb-5">Explore articles about programming, design, business and technology.</p>
            </div>
        </div>
        <div class="container py-5 my-5 reveal">
            <h1 class="display-4 fw-semibold text-center mb-5">Categories</h1>
            <div class="row g-5">
                <div class="col-md-4 text-center ">
                    <a href="{{ route('posts.category') }}?category={{ 82 }}" class="img-animation">
                        <img src="{{ asset('storage/posts/Programming.webp') }}" class="card-img-top img-fluid img-post"
                            style="min-height: 200px; max-height: 200px;" alt="">
                    </a>
                    <div class="card-title text-center fs-4 fw-bold py-3"><a href="{{ route('posts.category') }}?category={{ 82 }}"
                            class="link-animation text-decoration-none">Programming</a></div>
                </div>
                <div class="col-md-4 reveal text-center">
                    <a href="{{ route('posts.category') }}?category={{ 81 }}" class="img-animation">
                        <img src="{{ asset('storage/posts/Design.jpg') }}" class="card-img-top img-fluid img-post"
                            style="min-height: 200px; max-height: 200px;" alt="">
                    </a>
                    <div class="card-title text-center fs-4 fw-bold py-3"><a href="{{ route('posts.category') }}?category={{ 81 }}"
                            class="link-animation text-decoration-none">Design</a></div>
                </div>
                <div class="col-md-4 reveal text-center">
                    <a href="{{ route('posts.category') }}?category={{ 83 }}" class="img-animation">
                        <img src="{{ asset('storage/posts/Business.webp') }}" class="card-img-top img-fluid img-post"
                            style="min-height: 200px; max-height: 200px;" alt="">
                    </a>
                    <div class="card-title text-center fs-4 fw-bold py-3"><a href="{{ route('posts.category') }}?category={{ 83 }}"
                            class="link-animation text-decoration-none">Business</a></div>
                </div>
            </div>
        </div>
        <!-- Featured Post -->
        <div class="featured-post container reveal">
            <h1 class="display-4 fw-bold text-center mb-5" style="color: #2c3e50;">Featured Post</h1>
            <div class="d-flex flex-column justify-content-center mx-auto mb-4 hover-lift p-4 bg-white rounded-4 shadow-sm border" style="max-width: 80%; transition: all 0.3s ease;">
                <div class="position-relative mx-auto mb-4">
                    <img src="{{ Str::startsWith($featured->image, ['http://', 'https://']) ? $featured->image : asset('storage/' . $featured->image) }}"
                        class="featured-img w-100 rounded-4" style="object-fit: cover; max-height: 450px;" alt="{{ $featured->title }}">
                    <div class="d-flex gap-5 overlay-text position-absolute w-100 bottom-0 start-0 rounded-bottom-4" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); padding: 2rem 3rem 1rem;">
                        <div>
                            <p class="fw-light">Written By</p>
                            <p class="fs-5" style="font-weight: 500">
                                {{ $featured->user_id ? $featured->user->name : 'Unknown' }}</p>
                        </div>
                        <div>
                            <p class="fw-light">Published on</p>
                            <p class="ms-auto fs-5" style="font-weight: 500">
                                @php
                                    $datetime = Carbon::parse($featured->published_at);
                                    echo $datetime->format('d M Y');
                                @endphp
                            </p>
                        </div>
                    </div>
                </div>
                <div class="">
                    <div class="position-relative">
                        <div style="max-width: 90%">
                            <span class="card-title fs-4 fw-bold"><a href="{{ route('posts.show', $featured->id) }}"
                                    class="link-animation text-decoration-none">{{ $featured->title }}</a></span>
                        </div>
                        <i class="bi bi-arrow-up-right position-absolute top-0 end-0 mt-1 fw-bolder fs-4"></i>
                    </div>
                    <h6 class="card-title text-muted fw-normal mb-4" style="line-height: 1.6;">
                        {{ Str::limit($featured->description, 150) }} </h6>
                    <h6 class="card-title fw-normal d-inline border border-1 text-primary border-primary rounded-pill fw-semibold bg-light"
                        style="padding: 5px 20px;">{{ Str::limit($featured->category->name, 18) }}</h6>
                </div>
            </div>
        </div>

    </div>
    <div class="p-5 mt-5 bg-light rounded-top-5">
        <h1 class="display-4 fw-bold text-center mb-5" style="color: #2c3e50;">Latest Posts</h1>
        <div class="row g-5">
            @foreach ($latest as $post)
                <div class="col-md-4 reveal">
                    <div class="card h-100 border-0 shadow-sm rounded-4 hover-lift overflow-hidden">
                        <a href="{{ route('posts.show', $post->id) }}" class="img-animation d-block overflow-hidden">
                            <img src="{{ Str::startsWith($post->image, ['http://', 'https://']) ? $post->image : asset('storage/' . $post->image) }}"
                                class="img-fluid img-post w-100" style="height: 220px; object-fit: cover;" alt="{{ $post->title }}">
                        </a>
                        <div class="card-body p-4 d-flex flex-column" id="{{ $post->id }}">
                            <h6 class="fw-semibold">
                                {{ $post->user->name }} •
                                @php
                                    $datetime = Carbon::parse($post->published_at);
                                    echo $datetime->format('d M Y');
                                @endphp
                            </h6>
                            <div class="position-relative mb-3">
                                <div style="max-width: 90%">
                                    <span class="card-title fs-4 fw-bold"><a href="{{ route('posts.show', $post->id) }}"
                                            class="link-animation text-decoration-none">{{ $post->title }}</a></span>
                                </div>
                                <i class="bi bi-arrow-up-right position-absolute top-0 end-0 mt-1 fw-bolder fs-4"></i>
                            </div>
                            <h6 class="card-title text-muted fw-normal mb-4 flex-grow-1" style="line-height: 1.5;">
                                {{ Str::limit($post->description, 100) }} </h6>
                            <div class="mt-auto">
                                <h6 class="card-title fw-normal d-inline border border-1 text-primary border-primary rounded-pill fw-semibold bg-light"
                                    style="padding: 4px 15px; font-size: 0.9rem;">{{ Str::limit($post->category->name, 18) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row g-5 mt-4">
            <h1 class="display-4 fw-bold text-center mb-5" style="color: #2c3e50;">Popular Posts</h1>
            @foreach ($mostPopular as $post)
                <div class="col-md-4 reveal">
                    <div class="card h-100 border-0 shadow-sm rounded-4 hover-lift overflow-hidden">
                        <a href="{{ route('posts.show', $post->id) }}" class="img-animation d-block overflow-hidden">
                            <img src="{{ Str::startsWith($post->image, ['http://', 'https://']) ? $post->image : asset('storage/' . $post->image) }}"
                                class="img-fluid img-post w-100" style="height: 220px; object-fit: cover;" alt="{{ $post->title }}">
                        </a>
                        <div class="card-body p-4 d-flex flex-column" id="{{ $post->id }}">
                            <h6 class="fw-semibold">
                                {{ $post->user->name }} •
                                @php
                                    $datetime = Carbon::parse($post->published_at);
                                    echo $datetime->format('d M Y');
                                @endphp
                            </h6>
                            <div class="position-relative mb-3">
                                <div style="max-width: 90%">
                                    <span class="card-title fs-4 fw-bold"><a href="{{ route('posts.show', $post->id) }}"
                                            class="link-animation text-decoration-none">{{ $post->title }}</a></span>
                                </div>
                                <i class="bi bi-arrow-up-right position-absolute top-0 end-0 mt-1 fw-bolder fs-4"></i>
                            </div>
                            <h6 class="card-title text-muted fw-normal mb-4 flex-grow-1" style="line-height: 1.5;">
                                {{ Str::limit($post->description, 100) }} </h6>
                            <div class="mt-auto">
                                <h6 class="card-title fw-normal d-inline border border-1 text-primary border-primary rounded-pill fw-semibold bg-light"
                                    style="padding: 4px 15px; font-size: 0.9rem;">{{ Str::limit($post->category->name, 18) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    </div>
@endsection --}}
