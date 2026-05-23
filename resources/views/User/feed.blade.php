@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
@endphp
@extends('layouts.app')
@section('title')
    Your Feed — OLog
@endsection
@section('content')

    {{-- ===================== HERO HEADER ===================== --}}
    <div class="cat-hero mb-5">
        <div class="cat-hero-bg"></div>
        <div class="cat-hero-content container">
            {{-- Breadcrumb --}}
            <nav class="cat-breadcrumb" aria-label="breadcrumb">
                <a href="{{ route('Home') }}" class="cat-breadcrumb-link">
                    <i class="bi bi-house-fill"></i> Home
                </a>
                <span class="cat-breadcrumb-sep"><i class="bi bi-chevron-right"></i></span>
                <span class="cat-breadcrumb-link">Your Feed</span>
            </nav>

            {{-- Title --}}
            <h1 class="cat-hero-title">
                <span class="cat-hero-label">Network</span>
                Your Feed
            </h1>

            <p class="cat-hero-sub">
                Latest posts from authors you follow.
            </p>
        </div>
    </div>

    <div class="container mb-5 pb-5">
        <div class="row g-5">

            {{-- MAIN FEED COLUMN --}}
            <div class="col-lg-8">
                @if (!$noFollows && $posts && $posts->count() > 0)
                    <div class="d-flex flex-column gap-5">
                        @foreach ($posts as $post)
                            <div class="cat-card2 border-0 shadow-sm" id="feedPost-{{ $post->id }}">

                                {{-- Author Header --}}
                                <div class="bg-white border-bottom p-4 d-flex align-items-center justify-content-between">
                                    <a href="{{ route('user.profile', $post->user->id) }}"
                                        class="d-flex align-items-center gap-3 text-decoration-none text-dark link-animation">
                                        @if ($post->user->image)
                                            <img src="{{ asset('storage/' . $post->user->image) }}"
                                                class="rounded-circle shadow-sm" width="50" height="50"
                                                alt="{{ $post->user->name }}" style="object-fit:cover;">
                                        @else
                                            <div class="profile-circle"
                                                style="width:50px !important; height:50px !important">
                                                {{ strtoupper(substr($post->user->name, 0, 2)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0 fw-bold fs-5">
                                                {{ $post->user->name }}
                                                @if (isset($post->user->is_verified) && $post->user->is_verified)
                                                    <i class="bi bi-patch-check-fill text-primary ms-1"
                                                        style="font-size: 0.95rem;"></i>
                                                @endif
                                            </h6>
                                            <small
                                                class="text-muted">{{ Carbon::parse($post->published_at)->diffForHumans() }}</small>
                                        </div>
                                    </a>
                                    <div>
                                        <x-follow-button id="{{ $post->user->id }}"></x-follow-button>
                                    </div>
                                </div>

                                {{-- Text Content --}}
                                <div class="p-4 pb-3">
                                    <h4 class="fw-bold mb-3">
                                        <a href="{{ route('posts.show', $post->id) }}"
                                            class="text-dark text-decoration-none link-animation">
                                            {{ $post->title }}
                                        </a>
                                    </h4>
                                    <p class="text-muted" style="font-size: 1.1rem; line-height: 1.6;">
                                        {{ Str::limit($post->description, 250) }}
                                    </p>
                                </div>

                                {{-- Image --}}
                                <a href="{{ route('posts.show', $post->id) }}"
                                    class="d-block mx-4 mb-4 rounded-4 overflow-hidden" style="max-height: 450px;">
                                    <img src="{{ Str::startsWith($post->image, ['http://', 'https://']) ? $post->image : asset('storage/' . $post->image) }}"
                                        class="w-100 h-100 object-fit-cover img-animation" alt="{{ $post->title }}">
                                </a>

                                {{-- Category Tag --}}
                                <div class="px-4 mb-4">
                                    <a href="{{ route('posts.index') }}?category={{ $post->category_id }}"
                                        class="cat-card2-category-badge d-inline-block">
                                        {{ $post->category->name ?? 'General' }}
                                    </a>
                                </div>

                                {{-- Actions Footer --}}
                                <div class="bg-light border-top p-3 px-4 d-flex align-items-center gap-3">
                                    {{-- Like --}}
                                    <button
                                        class="btn btn-white border shadow-sm rounded-pill px-4 py-2 text-secondary fw-bold button-pop likeBtn"
                                        data-url="{{ route('posts.like', [$post->id, auth()->id()]) }}"
                                        data-post-id="{{ $post->id }}">
                                        @if ($post->likedByUsers->isNotEmpty())
                                            <i class="bi bi-hand-thumbs-up-fill text-primary fs-5"></i>
                                        @else
                                            <i class="bi bi-hand-thumbs-up fs-5"></i>
                                        @endif
                                        <span id="likeCounter-{{ $post->id }}"
                                            class="ms-2">{{ $post->likes->count() }}</span>
                                    </button>

                                    {{-- Comment --}}
                                    <button
                                        class="btn btn-white border shadow-sm rounded-pill px-4 py-2 text-secondary fw-bold button-pop"
                                        onclick="document.getElementById('feedComment-{{ $post->id }}').focus()">
                                        <i class="bi bi-chat-text fs-5"></i> <span
                                            class="ms-2">{{ $post->comments->count() }}</span>
                                    </button>

                                    {{-- Save --}}
                                    <button
                                        class="btn btn-white border shadow-sm rounded-pill px-4 py-2 text-secondary fw-bold button-pop saveBtn"
                                        data-url="{{ route('posts.save', $post->id) }}">
                                        @if (auth()->user()->savedPosts()->where('post_id', $post->id)->exists())
                                            <i class="bi bi-bookmark-fill text-primary fs-5"></i>
                                        @else
                                            <i class="bi bi-bookmark fs-5"></i>
                                        @endif
                                    </button>

                                    {{-- Read --}}
                                    <a href="{{ route('posts.show', $post->id) }}"
                                        class="btn btn-dark ms-auto rounded-pill px-4 py-2 fw-bold button-pop">
                                        Read <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>

                                {{-- Comments Section --}}
                                <div class="bg-white p-4 pt-4 border-top">
                                    {{-- Comment Form --}}
                                    <form class="d-flex gap-3 mb-4 commentForm" url="{{ route('comment.store') }}"
                                        data-deleteurl="{{ url('/comment/') }}/" postId="{{ $post->id }}" data-style="chat">
                                        @csrf
                                        <input type="text" name="body"
                                            class="form-control rounded-pill bg-light border-0 px-4 py-2 commentInput glass"
                                            id="feedComment-{{ $post->id }}" placeholder="Write a comment..."
                                            autocomplete="off">
                                        <input type="hidden" name="post_id" value="{{ $post->id }}">
                                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                        <button type="submit"
                                            class="btn btn-primary rounded-circle shadow p-0 d-flex align-items-center justify-content-center button-pop"
                                            style="width:44px; height:44px; flex-shrink:0;">
                                            <i class="bi bi-send-fill text-white"></i>
                                        </button>
                                    </form>
                                    <x-form-error name="body-{{ $post->id }}"></x-form-error>

                                    {{-- Comments List --}}
                                    <div id="commentsContainer-{{ $post->id }}" class="d-flex flex-column gap-3">
                                        @foreach ($post->comments->take(3) as $comment)
                                            <div class="d-flex gap-3" id="comment-{{ $comment->id }}">
                                                <a href="{{ route('user.profile', $comment->user->id) }}">
                                                    <x-user-profile :post="$comment" width="38"
                                                        height="38"></x-user-profile>
                                                </a>
                                                <div class="bg-light p-3 rounded-4 rounded-top-0 border shadow-sm w-100">
                                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                                        <div>
                                                            <a href="{{ route('user.profile', $comment->user->id) }}"
                                                                class="fw-bold text-dark text-decoration-none link-animation">{{ $comment->user->name }}</a>
                                                            <small class="text-muted ms-2"
                                                                style="font-size:0.8rem;">{{ Carbon::parse($comment->created_at)->diffForHumans() }}</small>
                                                        </div>
                                                        @can('delete-comment', $comment)
                                                            <div class="dropdown">
                                                                <button class="btn btn-sm btn-link text-muted p-0 border-0"
                                                                    data-bs-toggle="dropdown"><i
                                                                        class="bi bi-three-dots"></i></button>
                                                                <ul
                                                                    class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                                                                    <form url="{{ route('comment.delete', $comment->id) }}"
                                                                        data-id="{{ $comment->id }}" class="deleteComment">
                                                                        <li><button class="dropdown-item text-danger"
                                                                                type="submit"><i
                                                                                    class="bi bi-trash me-2"></i>
                                                                                Delete</button></li>
                                                                    </form>
                                                                </ul>
                                                            </div>
                                                        @endcan
                                                    </div>
                                                    <p class="mb-0 text-dark" style="font-size:1rem;">
                                                        {{ $comment->body }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                        @if ($post->comments->count() > 3)
                                            <a href="{{ route('posts.show', $post->id) }}#comments-section-start"
                                                class="text-primary fw-bold text-decoration-none mt-2 link-animation"
                                                style="font-size: 0.95rem;">
                                                View all {{ $post->comments->count() }} comments <i
                                                    class="bi bi-arrow-right"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if ($posts->hasPages())
                        <div class="cat-pagination mt-5">
                            {{ $posts->links() }}
                        </div>
                    @endif
                @else
                    {{-- Empty state --}}
                    <div class="text-center py-5 my-5 bg-white rounded-4 shadow-sm border glass">
                        <i class="bi bi-rss text-muted mb-3" style="font-size: 4rem;"></i>
                        <h3 class="fw-bold">Your feed is empty</h3>
                        <p class="text-muted mb-4">You're not following anyone yet, or they haven't posted anything.</p>
                        <a href="{{ route('posts.index') }}" class="btn btn-primary rounded-pill px-4 button-pop">
                            <i class="bi bi-compass"></i> Explore Writers
                        </a>
                    </div>
                @endif
            </div>

            {{-- RIGHT SIDEBAR --}}
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 100px;">

                    {{-- Network Box --}}
                    <div class="cat-card2 border-0 shadow-sm text-center p-4 mb-4 glass">
                        <div class="text-primary mb-3"><i class="bi bi-people-fill" style="font-size: 2.5rem;"></i></div>
                        <h5 class="fw-bold">Your Network</h5>
                        <p class="text-muted small mb-4">You're seeing posts from people you follow. Explore more authors
                            to enrich your feed.</p>
                        <a href="{{ route('posts.index') }}"
                            class="btn btn-outline-primary rounded-pill w-100 fw-bold button-pop">
                            <i class="bi bi-compass"></i> Discover Authors
                        </a>
                    </div>

                    {{-- Write Article Box --}}
                    @can('create', \App\Models\Post::class)
                        <div class="cat-card2 border-0 shadow text-center p-4 bg-dark text-white hero-gradient">
                            <div class="text-white-50 mb-3"><i class="bi bi-pencil-square" style="font-size: 2.5rem;"></i>
                            </div>
                            <h5 class="fw-bold text-white">Share Your Story</h5>
                            <p class="text-white-50 small mb-4">Have something to share? Write an article and reach your
                                audience.</p>
                            <a href="{{ route('posts.create') }}"
                                class="btn btn-light text-dark rounded-pill w-100 fw-bold border-0 button-pop">
                                Write Article <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    @endcan

                </div>
            </div>

        </div>
    </div>

@endsection
