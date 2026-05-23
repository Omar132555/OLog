@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
@endphp
@extends('layouts.app')
@section('title')
    Explore — Discover Articles
@endsection
@section('content')

{{-- ===================== HERO HEADER (Bootstrap + inline style) ===================== --}}


    <div class="cat-hero">
        <div class="cat-hero-bg"></div>
        <div class="cat-hero-content container">
            {{-- Breadcrumb --}}
            <nav class="cat-breadcrumb" aria-label="breadcrumb">
                <a href="{{ route('Home') }}" class="cat-breadcrumb-link">
                    <i class="bi bi-house-fill"></i> Home
                </a>
                <span class="cat-breadcrumb-sep"><i class="bi bi-chevron-right"></i></span>
                <span class="cat-breadcrumb-link">Browse</span>
            </nav>

            {{-- Title --}}
            <h1 class="cat-hero-title">

                    <span class="cat-hero-label">Explore</span>
                    Explore Articles
            </h1>

            <p class="cat-hero-sub">
                Discover stories, ideas, and expertise from our writers.
            </p>

        {{-- Search Bar using existing .glass --}}
        <form action="{{ route('posts.index') }}" method="GET" class="d-flex justify-content-center">
            @if($selectedId)
                <input type="hidden" name="category" value="{{ $selectedId }}">
            @endif
            @if(request('sort') && request('sort') !== 'latest')
                <input type="hidden" name="sort" value="{{ request('sort') }}">
            @endif
            
            <div class="glass d-flex align-items-center p-2 rounded-pill shadow-lg" style="width: 100%;">
                <i class="bi bi-search ms-3 text-dark"></i>
                <input type="text" name="q" placeholder="Search titles, topics, or keywords…"
                       value="{{ request('q') }}"
                       class="form-control border-0 shadow-none bg-transparent ms-2"
                       autocomplete="off">
                <button type="submit" class="btn btn-primary rounded-pill px-4 ms-2 button-pop">Search</button>
            </div>
        </form>

        </div>
    </div>

{{-- ===================== BODY LAYOUT ===================== --}}
<div class="container mt-5 mb-5">
    
    {{-- Categories & Filters Row --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-3 border-bottom">
        
        {{-- Sort Controls --}}
        <div class="d-flex gap-2">
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}"
               class="btn rounded-pill {{ request('sort', 'latest') === 'latest' ? 'btn-dark' : 'btn-outline-dark' }} px-3 py-1 button-pop">
                <i class="bi bi-clock"></i> Latest
            </a>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'popular']) }}"
               class="btn rounded-pill {{ request('sort') === 'popular' ? 'btn-dark' : 'btn-outline-dark' }} px-3 py-1 button-pop">
                <i class="bi bi-fire"></i> Popular
            </a>
        </div>
    </div>

    {{-- Info Bar for active search/filters --}}
    @if($selectedId || request('q'))
        <div class="alert alert-secondary d-flex align-items-center justify-content-between rounded-4 mb-4 shadow-sm border-0 glass">
            <div class="d-flex align-items-center gap-2">
                <strong>Filters applied:</strong>
                @if($selectedId)
                    <span class="badge bg-primary px-3 py-2 rounded-pill">
                        <i class="bi bi-tag-fill"></i> Category ID: {{ $selectedId }}
                    </span>
                @endif
                @if(request('q'))
                    <span class="badge bg-primary px-3 py-2 rounded-pill">
                        <i class="bi bi-search"></i> "{{ request('q') }}"
                    </span>
                @endif
            </div>
            <a href="{{ route('posts.index') }}" class="btn btn-sm btn-outline-dark rounded-pill button-pop">Clear All</a>
        </div>
    @endif

    {{-- Post grid or empty state --}}
            @if($posts->count() > 0)
                <div class="cat-grid2">
                    @foreach($posts as $post)
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
                                {{-- Meta top row --}}
                                <div class="cat-card2-meta">
                                    <a href="{{ route('posts.category') }}?category={{ $post->category->id }}"
                                       class="cat-card2-category-badge">
                                        {{ $post->category->name }}
                                    </a>
                                    <span class="cat-card2-date">
                                        <i class="bi bi-calendar3"></i>
                                        {{ Carbon::parse($post->published_at)->format('d M Y') }}
                                    </span>
                                </div>

                                {{-- Title --}}
                                <h2 class="cat-card2-title">
                                    <a href="{{ route('posts.show', $post->id) }}">
                                        {{ Str::limit($post->title, 60) }}
                                    </a>
                                </h2>

                                {{-- Excerpt --}}
                                <p class="cat-card2-excerpt">
                                    {{ Str::limit($post->description, 110) }}
                                </p>

                                {{-- Footer — profile link only accessible to auth users --}}
                                <div class="cat-card2-footer">
                                    @auth
                                    <a href="{{ route('user.profile', $post->user->id) }}" class="cat-card2-author">
                                    @else
                                    <span class="cat-card2-author">
                                    @endauth
                                        <x-user-profile :post="$post" width="42" height="42"></x-user-profile>
                                        <span class="cat-card2-author-name">{{ $post->user->name }}</span>
                                    @auth
                                    </a>
                                    @else
                                    </span>
                                    @endauth
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

                {{-- Pagination --}}
                @if($posts->hasPages())
                    <div class="cat-pagination">
                        {{ $posts->appends(request()->query())->links() }}
                    </div>
                @endif

    @else
        {{-- Empty state --}}
        <div class="text-center py-5 my-5 bg-white rounded-4 shadow-sm border glass">
            <i class="bi bi-journal-x text-muted mb-3" style="font-size: 4rem;"></i>
            <h3 class="fw-bold">No articles found</h3>
            <p class="text-muted mb-4">We couldn't find any articles matching your filters.</p>
            <a href="{{ route('posts.index') }}" class="btn btn-primary rounded-pill px-4 button-pop">Clear Filters</a>
        </div>
    @endif

</div>

@endsection
