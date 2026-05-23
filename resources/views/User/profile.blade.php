@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
@endphp
@extends('layouts.app')
@section('title')
    {{ $user->name }} — Profile
@endsection
@section('content')

{{-- ===================== COVER & PROFILE HEADER ===================== --}}
<div class="position-relative" style="">
    {{-- Cover Image --}}
    <div class="position-relative w-100" style="height: 390px; overflow: hidden;">
        @if ($user->image2)
            <img src="{{ asset('storage/' . $user->image2) }}" class="w-100 h-100" style="object-fit: cover; filter: brightness(0.7);" alt="Cover">
        @else
            <div class="w-100 h-100 hero-gradient d-flex justify-content-center align-items-center">
                <span class="text-white fw-bold opacity-25" style="font-size: 8rem; letter-spacing: 10px;">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </span>
            </div>
        @endif
        {{-- Dark gradient overlay --}}
        <div class="position-absolute w-100" style="bottom:0; left:0; height:160px; background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); pointer-events:none;"></div>
    </div>

    {{-- Profile Info (overlaid at bottom of cover) --}}
    <div class="container position-relative" style="margin-top: -180px; z-index: 10;">
        <div class="d-flex flex-wrap align-items-end gap-4 pb-4">
            {{-- Avatar --}}
            <div class="flex-shrink-0">
                @if ($user->image)
                    <img src="{{ asset('storage/' . $user->image) }}" class="rounded-circle shadow-lg border border-4 border-white"
                         style="width: 160px; height: 160px; object-fit: cover;" alt="{{ $user->name }}">
                @else
                    <div class="rounded-circle shadow-lg border border-4 border-white bg-primary d-flex justify-content-center align-items-center text-white"
                         style="width: 160px; height: 160px; font-size: 3.5rem; font-weight: 800;">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                @endif
            </div>

            {{-- Name & Actions --}}
            <div class="flex-grow-1 pb-2">
                <div class="d-flex flex-wrap align-items-center gap-3 mb-2">
                    <h1 class="fw-bold text-white m-0" style="font-size: 2.5rem;">{{ $user->name }}</h1>
                    @if ($user->is_verified)
                        <i class="bi bi-patch-check-fill text-primary" style="font-size: 1.75rem;"></i>
                    @endif
                </div>

                @if($user->bio)
                    <p class="text-white-50 mb-3" style="font-size: 1.1rem; max-width: 600px; line-height: 1.6;">
                        {{ $user->bio }}
                    </p>
                @endif

                {{-- Action Buttons --}}
                <div class="d-flex flex-wrap gap-3">
                    @if ($user->id != auth()->id())
                        <a href="{{ route('chat.index') }}?user_id={{ $user->id }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow button-pop text-center">
                            <i class="bi bi-chat-fill me-2"></i> Message
                        </a>
                        <x-follow-button :id="$user->id"></x-follow-button>
                    @else
                        <a href="{{ route('user.dashboard') }}" class="btn btn-outline-light rounded-pill px-4 py-2 fw-bold button-pop">
                            <i class="bi bi-pencil-square me-2"></i> Edit Profile
                        </a>
                    @endif
                </div>
            </div>

            {{-- Stats (pushed to right on desktop) --}}
            <div class="d-flex gap-3 ms-lg-auto pb-2">
                <div class="glass-panel text-center px-4 py-3 hover-lift" style="cursor: pointer; min-width: 100px; border-radius: 16px;" data-bs-toggle="modal" data-bs-target="#followersModal">
                    <h3 class="fw-bold m-0 text-white">{{ $user->followers_count ?? $user->followers->count() }}</h3>
                    <small class="text-white-50 fw-medium">Followers</small>
                </div>
                <div class="glass-panel text-center px-4 py-3 hover-lift" style="min-width: 100px; border-radius: 16px;">
                    <h3 class="fw-bold m-0 text-white">{{ $user->following->count() }}</h3>
                    <small class="text-white-50 fw-medium">Following</small>
                </div>
                <div class="glass-panel text-center px-4 py-3 hover-lift" style="min-width: 100px; border-radius: 16px;">
                    <h3 class="fw-bold m-0 text-white">{{ $user->posts->count() }}</h3>
                    <small class="text-white-50 fw-medium">Posts</small>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- ===================== POSTS SECTION ===================== --}}
<div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="fw-bold m-0"><i class="bi bi-journal-richtext text-primary me-2"></i> Posts by {{ $user->name }}</h2>
        <span class="text-muted fw-medium">{{ $user->posts->count() }} {{ Str::plural('article', $user->posts->count()) }}</span>
    </div>

    @if($user->posts->count() > 0)
        <div class="cat-grid2">
            @foreach ($user->posts as $post)
                <article class="cat-card2">
                    {{-- Image --}}
                    <a href="{{ route('posts.show', $post->id) }}" class="cat-card2-img-wrap">
                        <img src="{{ Str::startsWith($post->image, ['http://', 'https://'])
                            ? $post->image
                            : asset('storage/' . $post->image) }}"
                            class="cat-card2-img" alt="{{ $post->title }}">
                        <div class="cat-card2-img-overlay">
                            <span class="cat-card2-read-label">
                                Read Article <i class="bi bi-arrow-up-right"></i>
                            </span>
                        </div>
                    </a>

                    {{-- Body --}}
                    <div class="cat-card2-body">
                        <div class="cat-card2-meta">
                            <a href="{{ route('posts.category') }}?category={{ $post->category->id }}"
                               class="cat-card2-category-badge">
                                {{ $post->category->name ?? 'General' }}
                            </a>
                            <span class="cat-card2-date">
                                <i class="bi bi-calendar3"></i>
                                {{ Carbon::parse($post->published_at)->format('d M Y') }}
                            </span>
                        </div>

                        <h2 class="cat-card2-title">
                            <a href="{{ route('posts.show', $post->id) }}">
                                {{ Str::limit($post->title, 60) }}
                            </a>
                        </h2>

                        <p class="cat-card2-excerpt">
                            {{ Str::limit($post->description, 110) }}
                        </p>

                        <div class="cat-card2-footer">
                            <div class="cat-card2-author">
                                <x-user-profile :post="$post" width="32" height="32"></x-user-profile>
                                <span class="cat-card2-author-name">{{ $post->user->name }}</span>
                            </div>
                            <div class="cat-card2-stats">
                                <span class="cat-card2-stat">
                                    <i class="bi bi-hand-thumbs-up"></i>
                                    {{ $post->likes->count() }}
                                </span>
                                <span class="cat-card2-stat">
                                    <i class="bi bi-chat-text"></i>
                                    {{ $post->comments->count() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @else
        <div class="text-center py-5 my-3 bg-white rounded-4 shadow-sm border">
            <i class="bi bi-journal-x text-muted mb-3" style="font-size: 3.5rem;"></i>
            <h4 class="fw-bold text-dark">No posts yet</h4>
            <p class="text-muted">{{ $user->name }} hasn't published any articles yet.</p>
        </div>
    @endif
</div>


{{-- ===================== SUGGESTIONS SECTION ===================== --}}
@if($suggestions->count() > 0)
<div class="bg-white border-top py-5">
    <div class="container">
        <h2 class="fw-bold text-center mb-4"><i class="bi bi-people text-primary me-2"></i> People You May Know</h2>

        <div id="suggestCarousel" class="carousel slide" data-bs-interval="false">
            <div class="carousel-inner">
                @foreach ($suggestions->chunk(4) as $chunk)
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                        <div class="d-flex justify-content-center gap-4 px-5 py-3">
                            @foreach ($chunk as $suggestedUser)
                                <div class="suggest-card">
                                    <div class="avatar mx-auto my-3">
                                        @if ($suggestedUser->image)
                                            <a href="{{ route('user.profile', $suggestedUser->id) }}" class="text-decoration-none link-animation">
                                                <img src="{{ asset('storage/' . $suggestedUser->image) }}">
                                            </a>
                                        @else
                                            <a href="{{ route('user.profile', $suggestedUser->id) }}" class="text-decoration-none link-animation">
                                                <div class="fallback mx-auto">
                                                    {{ strtoupper(substr($suggestedUser->name, 0, 2)) }}
                                                </div>
                                            </a>
                                        @endif
                                    </div>
                                    <a href="{{ route('user.profile', $suggestedUser->id) }}" class="text-decoration-none link-animation">
                                        <h6 class="mt-2 mb-1 fw-bold">{{ $suggestedUser->name }}</h6>
                                    </a>
                                    <small class="text-muted">
                                        {{ $suggestedUser->mutual_counts }} mutual
                                    </small>
                                    <div class="my-3">
                                        <button data-id="{{ $suggestedUser->id }}" url="{{ route('follow') }}" class="follow-btn followBtn">
                                            Follow
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            @if($suggestions->count() > 4)
                <button class="carousel-control-prev" type="button" data-bs-target="#suggestCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#suggestCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            @endif
        </div>
    </div>
</div>
@endif


{{-- ===================== FOLLOWERS MODAL ===================== --}}
<div class="modal fade" id="followersModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header bg-dark text-white border-0 py-3 px-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-people-fill me-2"></i> Followers</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" style="max-height: 400px; overflow-y: auto;">
                @forelse ($user->followers()->get() as $follower)
                    <div class="d-flex align-items-center justify-content-between mb-3 p-2 rounded-3 hover-lift" style="transition: background 0.2s;">
                        <a href="{{ route('user.profile', $follower->id) }}" class="d-flex align-items-center gap-3 text-decoration-none text-dark link-animation">
                            @if ($follower->image)
                                <img src="{{ asset('storage/' . $follower->image) }}" class="rounded-circle shadow-sm" width="45" height="45" style="object-fit: cover;" alt="">
                            @else
                                <div class="profile-circle" style="width:45px !important; height:45px !important">
                                    {{ strtoupper(substr($follower->name, 0, 2)) }}
                                </div>
                            @endif
                            <span class="fw-bold">{{ $follower->name }}</span>
                        </a>
                        <button data-id="{{ $follower->id }}" url="{{ route('follow') }}" class="follow-btn followBtn">
                            @if (auth()->user()->following()->where('following_id', $follower->id)->exists())
                                Following
                            @else
                                Follow
                            @endif
                        </button>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="bi bi-people text-muted" style="font-size: 2.5rem;"></i>
                        <p class="text-muted mt-2 mb-0">No followers yet</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
