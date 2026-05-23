@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
@endphp
@extends('layouts.app')
@section('title')
    {{ $category ? $category->name . ' — Browse Articles' : 'All Articles' }}
@endsection
@section('content')

    <div class="cat-page">

        {{-- ===================== HERO HEADER ===================== --}}
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
                    @if ($category)
                        <span class="cat-breadcrumb-sep"><i class="bi bi-chevron-right"></i></span>
                        <span class="cat-breadcrumb-current">{{ $category->name }}</span>
                    @endif
                </nav>

                {{-- Title --}}
                <h1 class="cat-hero-title">
                    @if ($category)
                        <span class="cat-hero-label">Category</span>
                        {{ $category->name }}
                    @else
                        <span class="cat-hero-label">Explore</span>
                        All Articles
                    @endif
                </h1>

                <p class="cat-hero-sub">
                    @if ($category)
                        {{ $posts->total() }} {{ Str::plural('article', $posts->total()) }} in this category
                    @else
                        Discover stories, ideas, and expertise from our writers.
                    @endif
                </p>

                {{-- Inline search - preserves category + sort state --}}
                <form action="{{ route('posts.category') }}" method="GET" class="cat-hero-search-form">
                    @if ($selectedId)
                        <input type="hidden" name="category" value="{{ $selectedId }}">
                    @endif
                    @if (request('sort') && request('sort') !== 'latest')
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif
                    <div class="cat-hero-search-wrap">
                        <i class="bi bi-search cat-hero-search-icon"></i>
                        <input type="text" name="q" placeholder="Search articles…" value="{{ request('q') }}"
                            class="cat-hero-search-input" id="catSearchInput" autocomplete="off">
                        <button type="submit" class="cat-hero-search-btn">Search</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ===================== BODY LAYOUT ===================== --}}
        <div class="cat-body container">

            {{-- LEFT SIDEBAR --}}
            <aside class="cat-sidebar">

                {{-- All Categories nav --}}
                <div class="cat-sidebar-section">
                    <h6 class="cat-sidebar-heading">
                        <i class="bi bi-grid-3x3-gap-fill"></i> Categories
                    </h6>
                    <nav class="cat-sidebar-nav">
                        <a href="{{ route('posts.category') }}"
                            class="cat-sidebar-link {{ !$selectedId ? 'active' : '' }}">
                            <span class="cat-sidebar-link-name">
                                <i class="bi bi-journals"></i> All Articles
                            </span>
                            <span class="cat-sidebar-count">{{ $totalPostCount }}</span>
                        </a>
                        @foreach ($allCategories as $cat)
                            <a href="{{ route('posts.category') }}?category={{ $cat->id }}"
                                class="cat-sidebar-link {{ $selectedId == $cat->id ? 'active' : '' }}">
                                <span class="cat-sidebar-link-name">
                                    <i class="bi bi-tag"></i> {{ $cat->name }}
                                </span>
                                <span class="cat-sidebar-count">{{ $cat->posts_count }}</span>
                            </a>
                        @endforeach
                    </nav>
                </div>

                {{-- Sort widget --}}
                <div class="cat-sidebar-section">
                    <h6 class="cat-sidebar-heading">
                        <i class="bi bi-sort-down"></i> Sort By
                    </h6>
                    <div class="cat-sort-btns">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}"
                            class="cat-sort-btn {{ request('sort', 'latest') === 'latest' ? 'active' : '' }}">
                            <i class="bi bi-clock"></i> Latest
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'popular']) }}"
                            class="cat-sort-btn {{ request('sort') === 'popular' ? 'active' : '' }}">
                            <i class="bi bi-fire"></i> Popular
                        </a>
                    </div>
                </div>

            </aside>

            {{-- MAIN CONTENT --}}
            <main class="cat-main">

                {{-- Active filters bar --}}
                @if ($category || request('q'))
                    <div class="cat-filters-bar">
                        @if ($category)
                            <div class="cat-filter-chip">
                                <i class="bi bi-tag-fill"></i>
                                {{ $category->name }}
                                <a href="{{ route('posts.category') }}{{ request('q') ? '?q=' . urlencode(request('q')) : '' }}"
                                    class="cat-filter-remove" title="Remove filter">
                                    <i class="bi bi-x"></i>
                                </a>
                            </div>
                        @endif
                        @if (request('q'))
                            <div class="cat-filter-chip">
                                <i class="bi bi-search"></i>
                                "{{ request('q') }}"
                                <a href="{{ route('posts.category') }}{{ $selectedId ? '?category=' . $selectedId : '' }}"
                                    class="cat-filter-remove" title="Clear search">
                                    <i class="bi bi-x"></i>
                                </a>
                            </div>
                        @endif
                        <span class="cat-result-count">
                            {{ $posts->total() }} {{ Str::plural('result', $posts->total()) }} found
                        </span>
                    </div>
                @endif

                {{-- Post grid or empty state --}}
                @if ($posts->count() > 0)
                    <div class="cat-grid2">
                        @foreach ($posts as $post)
                            <article class="cat-card2">

                                {{-- Image --}}
                                <a href="{{ route('posts.show', $post->id) }}" class="cat-card2-img-wrap">
                                    <img src="{{ Str::startsWith($post->image, ['http://', 'https://']) ? $post->image : asset('storage/' . $post->image) }}"
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
                                                <x-user-profile :post="$post" width="42"
                                                    height="42"></x-user-profile>
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
                    @if ($posts->hasPages())
                        <div class="cat-pagination">
                            {{ $posts->appends(request()->query())->links() }}
                        </div>
                    @endif
                @else
                    {{-- Empty state --}}
                    <div class="cat-empty-state">
                        <div class="cat-empty-icon">
                            <i class="bi bi-newspaper"></i>
                        </div>
                        <h3 class="cat-empty-title">No articles found</h3>
                        <p class="cat-empty-desc">
                            @if (request('q'))
                                No articles match "<strong>{{ request('q') }}</strong>".
                                Try a different search term or browse another category.
                            @elseif($category)
                                There are no articles in <strong>{{ $category->name }}</strong> yet.
                            @else
                                No articles have been published yet. Check back soon!
                            @endif
                        </p>
                        <a href="{{ route('posts.category') }}" class="cat-empty-btn">
                            <i class="bi bi-arrow-left"></i> Browse All Articles
                        </a>
                    </div>
                @endif

            </main>
        </div>

    </div>

@endsection
