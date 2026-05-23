@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;

    // Dynamically calculate reading time
    $wordCount = str_word_count(strip_tags($post->description));
    $readTime = max(1, ceil($wordCount / 200));

    // Check if post is saved by authenticated user
    $isSaved = auth()->check() ? auth()->user()->savedPosts()->where('post_id', $post->id)->exists() : false;

    // Fetch related articles (same category, excluding current post)
    $relatedPosts = \App\Models\Post::where('category_id', $post->category_id)
        ->where('id', '!=', $post->id)
        ->latest()
        ->take(3)
        ->get();
@endphp

@extends('layouts.app')

@section('title')
    {{ $post->title }} - OLog
@endsection

@section('content')
    {{-- ===================== NAMESPACED WRAPPER ===================== --}}
    <div class="post-show-page">

        {{-- ===================== HTML STRUCTURE ===================== --}}
        <div class="show-page-wrapper">
            <div class="container px-4">

                {{-- 1. Editorial Article Header --}}
                <header class="article-header">
                    <span class="article-category-badge">
                        {{ $post->category->name }}
                    </span>

                    <h1 class="article-title serif">{{ $post->title }}</h1>

                    <p class="article-subtitle">
                        {{ $post->description }}
                    </p>

                </header>

                {{-- 2. Wide Hero Image --}}
                <div class="post-hero-wrap">
                    <img src="{{ Str::startsWith($post->image, ['http://', 'https://']) ? $post->image : asset('storage/' . $post->image) }}"
                        class="post-hero-image" alt="{{ $post->title }}">
                </div>

                {{-- Meta details: Author & dynamic read-time --}}
                <div class="author-meta-row">
                    <div class="author-info-inline">
                        @auth
                        <a href="{{ route('user.profile', $post->user->id) }}" class="author-avatar-link">
                            @endauth
                            <x-user-profile :post="$post" width="54" height="54"></x-user-profile>
                        </a>
                        <div class="author-text-details">
                            @auth
                            <a href="{{ route('user.profile', $post->user->id) }}" class="author-name-text hover-primary">
                                @endauth
                                {{ $post->user_id ? $post->user->name : 'Unknown Author' }}
                            </a>
                            <span class="author-pub-date">
                                Published on
                                @php
                                    $datetime = Carbon::parse($post->published_at);
                                    echo $datetime->format('d M Y');
                                @endphp
                                &nbsp;·&nbsp;
                                <span class="text-primary fw-semibold">{{ $readTime }} min read</span>
                            </span>
                        </div>
                    </div>

                    {{-- Author Follow Button / Edit Controls --}}
                    @auth
                        <div class="d-flex align-items-center gap-3">
                            @if ($post->user->id != auth()->user()->id)
                                <x-follow-button :id="$post->user->id"></x-follow-button>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary px-3 py-2 my-auto rounded-pill">Your
                                    Article</span>
                                    <a href="{{ route('posts.edit', $post) }}" class="btn btn-primary">Edit Post</a>
                            @endif
                        </div>
                    @endauth
                </div>
                {{-- 3. Grid Content Layout --}}
                <div class="article-content-grid">

                    {{-- Left Sticky Sidebar --}}
                    <aside class="sticky-left-sidebar d-none d-lg-flex">

                        {{-- Author Widget --}}
                        <div class="sidebar-widget">
                            <div class="d-flex flex-column align-items-center text-center gap-3">
                                @auth
                                    <a href="{{ route('user.profile', $post->user->id) }}" class="author-avatar-link">
                                @endauth
                                <x-user-profile :post="$post" width="76" height="76"></x-user-profile>
                            </a>
                            <div>
                                <h5 class="fw-bold mb-1 d-flex align-items-center justify-content-center gap-1 mt-3">
                                    @auth
                                        <a href="{{ route('user.profile', $post->user->id) }}"
                                            class="text-decoration-none text-dark hover-primary">
                                            @endauth
                                            {{ $post->user_id ? $post->user->name : 'Unknown' }}
                                        </a>
                                        @if (isset($post->user->is_verified) && $post->user->is_verified)
                                            <i class="bi bi-patch-check-fill text-primary" title="Verified Creator"></i>
                                        @endif
                                    </h5>
                                    <p class="text-muted small mb-2">OLog Contributor</p>

                                    @if (isset($post->user->bio) && $post->user->bio)
                                        <p class="text-secondary small mb-0 px-1">{{ Str::limit($post->user->bio, 110) }}
                                        </p>
                                    @else
                                        <p class="text-secondary small mb-0 px-1 italic">Sharing insights, perspectives, and
                                            technologies with the OLog community.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Table of Contents Widget --}}
                        <div class="sidebar-widget">
                            <h6 class="fw-bold text-uppercase letter-spacing-1 small mb-3 text-secondary">Table of Contents
                            </h6>
                            <nav class="toc-menu">
                                <a href="#intro" class="toc-link active" id="toc-intro">
                                    <span>Introduction</span>
                                    <i class="bi bi-arrow-right-short"></i>
                                </a>
                                <a href="#body" class="toc-link" id="toc-body">
                                    <span>Article Body</span>
                                    <i class="bi bi-arrow-right-short"></i>
                                </a>
                                <a href="#comments-section-start" class="toc-link" id="toc-comments">
                                    <span>Comments</span>
                                    <i class="bi bi-arrow-right-short"></i>
                                </a>
                                @if ($relatedPosts->count() > 0)
                                    <a href="#related" class="toc-link" id="toc-related">
                                        <span>Related Posts</span>
                                        <i class="bi bi-arrow-right-short"></i>
                                    </a>
                                @endif
                            </nav>
                        </div>
                    </aside>

                    {{-- Right Main Reading Pane --}}
                    <main class="reading-pane">
                        <article class="reading-body" id="intro">

                            {{-- First paragraph: large lead and styled --}}
                            <p>
                                {{ $post->description }}
                            </p>

                            {{-- Section Anchor for TOC --}}
                            <div id="body" style="scroll-margin-top: 120px;"></div>

                            {{-- Simulating rich blog article flow with paragraph spacing --}}
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam pulvinar libero sodales,
                                interdum odio a, finibus leo. Duis hendrerit lorem vel velit maximus convallis. Suspendisse
                                pulvinar erat sit amet ex luctus pretium. Aliquam nec magna purus. Vivamus rhoncus erat ut
                                sem cursus molestie. Nam id eleifend eros. Integer vulputate eget urna ut congue.
                            </p>

                            <div class="pull-quote serif">
                                "Writing is the painting of the voice. A modern publishing experience allows ideas to be
                                expressed freely, sparking critical dialogue that shifts perspectives."
                            </div>

                            <p>
                                Quisque vel nisl a lectus convallis finibus. In sodales arcu magna, vel aliquet ante
                                eleifend in. Mauris interdum ipsum et neque facilisis convallis. Vestibulum sit amet justo
                                luctus, euismod ex pretium, vulputate risus. Class aptent taciti sociosqu ad litora torquent
                                per conubia nostra, per inceptos himenaeos. Proin imperdiet augue sit amet neque consequat
                                aliquet.
                            </p>

                            <p>
                                Duis id eros vel augue ultrices auctor in a dui. Phasellus non finibus quam, sed maximus
                                arcu. Cras ultrices, ligula quis dignissim feugiat, ante est mollis turpis, vel vulputate
                                nulla leo eget nulla. Suspendisse lobortis lacus non neque eleifend pretium.
                            </p>

                            {{-- Post Tags display --}}
                            <div class="post-tags-container">
                                <a href="{{ route('posts.category') }}?category={{ $post->category->id }}"
                                    class="post-tag-pill">
                                    #{{ $post->category->name }}
                                </a>
                                @if ($post->tags && $post->tags->count() > 0)
                                    @foreach ($post->tags as $tag)
                                        <a href="#" class="post-tag-pill">#{{ $tag->name }}</a>
                                    @endforeach
                                @else
                                    <a href="#" class="post-tag-pill">#OLogInsights</a>
                                    <a href="#" class="post-tag-pill">#Trending</a>
                                @endif
                            </div>
                        </article>
                    </main>
                </div>

                {{-- 4. Spectacular Comments Section --}}
                <section class="comments-section-container" id="comments-section-start" style="scroll-margin-top: 100px;">
                    <h3 class="comments-title serif">
                        Discussions
                        <span class="fs-4 text-muted ms-2">({{ $comments->count() }})</span>
                    </h3>

                    {{-- Comment Composer Box --}}
                    <div class="comment-compose-box">
                        @auth
                            <form url="{{ route('comment.store') }}" class="commentForm" id="commentForm"
                                data-deleteurl="{{ url('/comment/') }}/" postId="{{ $post->id }}">
                                @csrf
                                <textarea name="body" class="comment-composer-textarea" id="commentInput"
                                    placeholder="What are your thoughts on this article? Join the discussion..."></textarea>
                                <input type="hidden" name="post_id" value="{{ $post->id }}">
                                <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                                <div class="comment-composer-footer">
                                    <span class="comment-char-counter" id="charCounter">0 / 300 characters</span>
                                    <button type="submit" class="comment-post-btn">
                                        <span>Post Comment</span>
                                        <i class="bi bi-send-fill"></i>
                                    </button>
                                </div>
                            </form>
                            <div class="mt-2">
                                <x-form-error name="body-{{ $post->id }}"></x-form-error>
                            </div>
                        @endauth

                        @guest
                            <div class="text-center py-2">
                                <p class="text-secondary mb-3">You must be logged in to participate in the discussions.</p>
                                <button class="btn btn-dark rounded-pill px-4 py-2 hover-lift" data-bs-toggle="modal"
                                    data-bs-target="#loginModal">
                                    Login to Comment <i class="bi bi-box-arrow-in-right ms-2"></i>
                                </button>
                            </div>
                        @endguest
                    </div>

                    {{-- Comments Stream Container --}}
                    <div id="commentsContainer-{{ $post->id }}">
                        @if ($comments->count() > 0)
                            @foreach ($comments as $comment)
                                <div class="comment" id="comment-{{ $comment->id }}">
                                    <div class="d-flex gap-2 align-items-center">
                                        <a href="{{ route('user.profile', $comment->user->id) }}">
                                            <x-user-profile :post="$comment" width="45"
                                                height="45"></x-user-profile>
                                        </a>
                                        <div class="d-flex flex-column">
                                            <a href="{{ route('user.profile', $comment->user->id) }}"
                                                class="text-decoration-none link-animation">
                                                <h5 class="fw-semibold mb-0 text-dark hover-primary"
                                                    style="font-size: 1rem;">
                                                    {{ $comment->user->name }}
                                                </h5>
                                            </a>
                                            <span class="text-muted small" style="font-size: 0.8rem;">
                                                {{ Carbon::parse($comment->created_at)->diffForHumans() }}
                                            </span>
                                        </div>
                                        @auth
                                            @can('delete-comment', $comment)
                                                <div class="dropdown ms-auto">
                                                    <button class="border-0 delete-hovering" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <form url="{{ route('comment.delete', $comment->id) }}"
                                                            data-id="{{ $comment->id }}" class="deleteComment">
                                                            @csrf
                                                            <li><button class="dropdown-item text-danger"
                                                                    type="submit">Delete</button></li>
                                                        </form>
                                                    </ul>
                                                </div>
                                            @endcan
                                        @endauth
                                    </div>
                                    <div class="mt-2">
                                        <span class="comment-body-text">{{ $comment->body }}</span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-comments-state" id="emptyCommentsState">
                                <i class="bi bi-chat-heart empty-comments-icon"></i>
                                <h5 class="empty-comments-title">No comments yet</h5>
                                <p class="empty-comments-desc">Be the first to share your thoughts and spark a discussion!
                                </p>
                            </div>
                        @endif
                    </div>
                </section>

                {{-- 5. Related Articles Section --}}
                @if ($relatedPosts->count() > 0)
                    <section class="related-posts-section mb-5" id="related" style="scroll-margin-top: 100px;">
                        <h3 class="related-title serif">You Might Also Like</h3>
                        <div class="row g-4">
                            @foreach ($relatedPosts as $related)
                                <div class="col-md-4">
                                    <div class="related-card">
                                        <div class="related-card-img-wrap">
                                            <a href="{{ route('posts.show', $related->id) }}">
                                                <img src="{{ Str::startsWith($related->image, ['http://', 'https://']) ? $related->image : asset('storage/' . $related->image) }}"
                                                    class="related-card-img" alt="{{ $related->title }}">
                                            </a>
                                        </div>
                                        <div class="related-card-body">
                                            <p class="related-card-meta">
                                                {{ $related->category->name }}
                                            </p>
                                            <h4 class="related-card-title">
                                                <a
                                                    href="{{ route('posts.show', $related->id) }}">{{ Str::limit($related->title, 55) }}</a>
                                            </h4>
                                            <p class="related-card-excerpt">
                                                {{ Str::limit($related->description, 95) }}
                                            </p>
                                            <div class="related-card-footer">
                                                <span
                                                    class="related-author-name text-muted">{{ $related->user->name }}</span>
                                                <a href="{{ route('posts.show', $related->id) }}"
                                                    class="related-read-more">
                                                    Read <i class="bi bi-arrow-up-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

            </div>
        </div>

        {{-- ===================== PERSISTENT WIDGETS ===================== --}}

        {{-- Share Link Copy Success Toast --}}
        <div class="share-toast" id="shareToast">
            <i class="bi bi-check-circle-fill"></i>
            <span>Article link copied to clipboard!</span>
        </div>

        {{-- Floating Interaction Glass Bar --}}
        <div class="sticky-action-bar" id="stickyActionBar">
            @auth
                {{-- Likes Interaction Button --}}
                <button class="action-bar-btn likeBtn" data-url="{{ route('posts.like', [$post->id, auth()->id()]) }}"
                    data-post-id="{{ $post->id }}">
                    @if ($isLiked)
                        <i class="bi bi-hand-thumbs-up-fill text-primary"></i>
                    @else
                        <i class="bi bi-hand-thumbs-up"></i>
                    @endif
                    <span class="action-count" id="likeCounter-{{ $post->id }}">
                        {{ $post->likes->count() }}
                    </span>
                </button>

                <div class="action-bar-divider"></div>

                {{-- Comments Scroller --}}
                <a href="#comments-section-start" class="action-bar-btn" id="actionCommentBtn">
                    <i class="bi bi-chat-text"></i>
                    <span class="action-count">{{ $post->comments->count() }}</span>
                </a>

                <div class="action-bar-divider"></div>

                {{-- Saved Posts/Bookmarked Interaction Button --}}
                <button class="action-bar-btn saveBtn" data-url="{{ route('posts.save', $post->id) }}">
                    @if ($isSaved)
                        <i class="bi bi-bookmark-fill text-primary"></i>
                    @else
                        <i class="bi bi-bookmark"></i>
                    @endif
                </button>

                <div class="action-bar-divider"></div>

                {{-- Copy Link / Share Post Action Button --}}
                <button class="action-bar-btn" onclick="copyShareLink(event, '{{ route('posts.show', $post->id) }}')">
                    <i class="bi bi-share"></i>
                </button>

                @can('edit-post', $post)
                    <div class="action-bar-divider"></div>

                    {{-- Edit Post Control --}}
                    <a href="{{ route('posts.edit', $post->id) }}" class="action-bar-btn hover-primary">
                        <i class="bi bi-pencil-square"></i>
                    </a>

                    <div class="action-bar-divider"></div>

                    {{-- AJAX Post Deletion Control --}}
                    <form url="{{ route('posts.destroy', $post->id) }}" id="deleteForm" style="display:inline;">
                        @csrf
                        <button class="action-bar-btn action-bar-btn-danger border-0 bg-transparent text-dark" type="submit">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                @endcan

            @endauth

            @guest
                {{-- Guest interaction links trigger Bootstrap Login Modal --}}
                <button data-bs-toggle="modal" data-bs-target="#loginModal" class="action-bar-btn">
                    <i class="bi bi-hand-thumbs-up"></i>
                    <span class="action-count">{{ $post->likes->count() }}</span>
                </button>

                <div class="action-bar-divider"></div>

                <button data-bs-toggle="modal" data-bs-target="#loginModal" class="action-bar-btn">
                    <i class="bi bi-chat-text"></i>
                    <span class="action-count">{{ $post->comments->count() }}</span>
                </button>

                <div class="action-bar-divider"></div>

                <button data-bs-toggle="modal" data-bs-target="#loginModal" class="action-bar-btn">
                    <i class="bi bi-bookmark"></i>
                </button>

                <div class="action-bar-divider"></div>

                <button class="action-bar-btn" onclick="copyShareLink(event, '{{ route('posts.show', $post->id) }}')">
                    <i class="bi bi-share"></i>
                </button>
            @endguest
        </div>

        {{-- 6. Login/Register Modal component --}}
        <x-modal></x-modal>

        {{-- ===================== JAVASCRIPT LOGIC ===================== --}}
        <script>
            // Smooth scrolling for Table of Contents anchors
            document.querySelectorAll('.toc-link, #actionCommentBtn').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 90,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Highlights active Table of Contents item as reader scrolls
            window.addEventListener('DOMContentLoaded', () => {
                const sections = [{
                        id: 'intro',
                        linkId: 'toc-intro'
                    },
                    {
                        id: 'body',
                        linkId: 'toc-body'
                    },
                    {
                        id: 'comments-section-start',
                        linkId: 'toc-comments'
                    }
                ];

                const relatedSection = document.getElementById('related');
                if (relatedSection) {
                    sections.push({
                        id: 'related',
                        linkId: 'toc-related'
                    });
                }

                const observerOptions = {
                    root: null,
                    rootMargin: '0px 0px -60% 0px',
                    threshold: 0
                };

                const sectionObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            // Remove active class from all links
                            sections.forEach(s => {
                                const link = document.getElementById(s.linkId);
                                if (link) link.classList.remove('active');
                            });

                            // Add active class to corresponding link
                            const activeSection = sections.find(s => s.id === entry.target.id);
                            if (activeSection) {
                                const activeLink = document.getElementById(activeSection.linkId);
                                if (activeLink) activeLink.classList.add('active');
                            }
                        }
                    });
                }, observerOptions);

                sections.forEach(s => {
                    const el = document.getElementById(s.id);
                    if (el) sectionObserver.observe(el);
                });
            });

            // Copy Share Link to Clipboard and trigger dynamic Toast
            function copyShareLink(event, url) {
                event.preventDefault();
                navigator.clipboard.writeText(url).then(() => {
                    const toast = document.getElementById("shareToast");
                    if (toast) {
                        toast.classList.add("visible");
                        setTimeout(() => {
                            toast.classList.remove("visible");
                        }, 3500);
                    }
                }).catch(err => {
                    console.error("Failed to copy share link: ", err);
                });
            }

            // Show/hide floating action bar on scroll
            window.addEventListener('scroll', () => {
                const bar = document.getElementById("stickyActionBar");
                if (bar) {
                    if (window.scrollY > 200) {
                        bar.classList.add("visible");
                    } else {
                        bar.classList.remove("visible");
                    }
                }
            });

            // Hide empty comments state automatically when a comment is added via AJAX
            document.addEventListener('DOMContentLoaded', () => {
                const commentsContainer = document.getElementById("commentsContainer-{{ $post->id }}");
                if (commentsContainer) {
                    const observer = new MutationObserver((mutations) => {
                        mutations.forEach((mutation) => {
                            if (mutation.addedNodes.length > 0) {
                                const emptyState = document.getElementById("emptyCommentsState");
                                if (emptyState) {
                                    emptyState.style.display = "none";
                                }
                            }
                        });
                    });
                    observer.observe(commentsContainer, {
                        childList: true
                    });
                }

                // Real-time character counter for comments composer box
                const composer = document.getElementById("commentInput");
                const counter = document.getElementById("charCounter");
                if (composer && counter) {
                    composer.addEventListener('input', function() {
                        const length = this.value.length;
                        counter.textContent = `${length} / 300 characters`;
                        if (length > 300) {
                            counter.classList.add("text-danger");
                            counter.classList.remove("text-secondary");
                        } else {
                            counter.classList.remove("text-danger");
                            counter.classList.add("text-secondary");
                        }
                    });
                }
            });
        </script>
    </div>
@endsection
