@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
@endphp
@extends('layouts.app')
@section('title')
    My Profile
@endsection

@section('content')
    <div class="d-flex" style="min-height: 100vh;">
        <!-- Sidebar -->
        <aside class="dashboard-sidebar">
            <div class="sidebar-nav">
                <ul class="list-unstyled">
                    <li class="sidebar-item {{ $section == 'profile' ? 'active' : '' }}">
                        <a href="{{ route('user.dashboard') }}?section=profile">
                            <i class="bi bi-person"></i>
                            <span>My Profile</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ $section == 'posts' ? 'active' : '' }}">
                        <a href="{{ route('user.dashboard') }}?section=posts">
                            <i class="bi bi-card-text"></i>
                            <span>My Posts</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ $section == 'notifications' ? 'active' : '' }}">
                        <a href="{{ route('user.dashboard') }}?section=notifications">
                            <i class="bi bi-bell"></i>
                            <span>Notifications</span>
                            @if ($user->unreadNotifications->count() > 0)
                                <span class="badge bg-danger rounded-pill ms-auto">
                                    {{ $user->unreadNotifications->count() }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li class="sidebar-item {{ $section == 'settings' ? 'active' : '' }}">
                        <a href="{{ route('user.dashboard') }}?section=settings">
                            <i class="bi bi-gear"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-fill" style="background: #f9fafb;">

            <!-- Toast Container -->
            <div class="toast-container" id="toastContainer"></div>

            @if ($section == 'profile')
                <!-- Profile Header -->
                <div class="profile-header">
                    <div class="cover-image-wrapper" data-bs-toggle="modal" data-bs-target="#changeProfileCover">
                        @if ($user->image2)
                            <img src="{{ asset('storage/' . $user->image2) }}" class="cover-image" alt="Cover">
                        @else
                            <div class="cover-image"></div>
                        @endif
                        <div class="cover-overlay">
                            <button class="edit-button">
                                <i class="bi bi-camera"></i>
                                Change Cover
                            </button>
                        </div>
                    </div>

                    <div class="profile-avatar-wrapper" data-bs-toggle="modal" data-bs-target="#changeProfile">
                        @if ($user->image)
                            <img src="{{ asset('storage/' . $user->image) }}" class="profile-avatar" alt="Profile">
                        @else
                            <div class="avatar-default">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                        @endif
                        <div class="avatar-edit-badge">
                            <i class="bi bi-camera"></i>
                        </div>
                    </div>
                </div>

                <!-- Profile Info -->
                <div class="profile-info">
                    <h1 class="profile-name">
                        {{ $user->name }}
                        @if ($user->is_verified)
                            <i class="bi bi-patch-check-fill verified-badge"></i>
                        @endif
                    </h1>

                    @if ($user->bio)
                        <p class="profile-bio">{{ $user->bio }}</p>
                    @endif

                    <div class="profile-stats">
                        <div class="stat-item">
                            <span class="stat-value">{{ $user->posts->count() }}</span>
                            <span class="stat-label">Posts</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">{{ $user->likes->count() }}</span>
                            <span class="stat-label">Likes</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">{{ $user->comments->count() }}</span>
                            <span class="stat-label">Comments</span>
                        </div>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="profile-tabs">
                    <a href="{{ route('user.dashboard') }}?section=profile&profile=account"
                        class="tab-link {{ ($profile ?? 'account') == 'account' ? 'active' : '' }}">
                        Account
                    </a>
                    <a href="{{ route('user.dashboard') }}?section=profile&profile=statistics"
                        class="tab-link {{ ($profile ?? '') == 'statistics' ? 'active' : '' }}">
                        Statistics
                    </a>
                    <a href="{{ route('user.dashboard') }}?section=profile&profile=about"
                        class="tab-link {{ ($profile ?? '') == 'about' ? 'active' : '' }}">
                        About
                    </a>
                </div>

                <!-- Content -->
                <div class="content-section">
                    @if (($profile ?? 'account') == 'account')
                        <!-- Email Verification Alert -->
                        @if (!$user->email_verified_at)
                            <div class="alert-box alert-warning">
                                <i class="bi bi-exclamation-triangle-fill alert-icon"></i>
                                <div class="flex-fill">
                                    <strong>Email Not Verified</strong>
                                    <p class="m-0">Please verify your email address to access all features.</p>
                                </div>
                                <a href="{{ route('email.show') }}" class="btn btn-warning">Verify Now</a>
                            </div>
                        @else
                            <div class="alert-box alert-success mb-4">
                                <i class="bi bi-check-circle-fill alert-icon"></i>
                                <div>
                                    <strong>Email Verified</strong>
                                </div>
                            </div>
                        @endif

                        <!-- Account Details -->
                        <div class="info-card">
                            <h2 class="card-title">Account Details</h2>
                            <form action="{{ route('user.update', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                        class="form-control @error('email') is-invalid @enderror" required>
                                    @error('email')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                        class="form-control @error('name') is-invalid @enderror" required>
                                    @error('name')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Bio</label>
                                    <textarea name="bio" id="bio" class="form-control @error('bio') is-invalid @enderror" rows="4"
                                        maxlength="160" placeholder="Write something about yourself...">{{ old('bio', $user->bio ?? '') }}</textarea>
                                    <div class="char-counter">
                                        <span id="charCount">{{ strlen($user->bio ?? '') }}</span>/160
                                    </div>
                                    @error('bio')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn-primary">
                                    <i class="bi bi-save me-2"></i>
                                    Save Changes
                                </button>
                            </form>
                        </div>

                        <!-- Password Change -->
                        <div class="info-card">
                            <h2 class="card-title">Change Password</h2>
                            <form action="{{ route('user.password.update', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label class="form-label">Current Password</label>
                                    <input type="password" name="current_password" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">New Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>

                                <button type="submit" class="btn-primary">
                                    <i class="bi bi-shield-lock me-2"></i>
                                    Update Password
                                </button>
                            </form>
                        </div>
                    @endif

                    @if (($profile ?? '') == 'statistics')
                        <div class="info-card">
                            <h2 class="card-title">Your Statistics</h2>

                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="text-center p-4"
                                        style="background: var(--secondary-color); border-radius: 12px;">
                                        <i class="bi bi-card-text"
                                            style="font-size: 48px; color: var(--primary-color);"></i>
                                        <h3 class="mt-3 mb-0">{{ $user->posts->count() }}</h3>
                                        <p class="text-muted m-0">Total Posts</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center p-4"
                                        style="background: var(--secondary-color); border-radius: 12px;">
                                        <i class="bi bi-heart-fill" style="font-size: 48px; color: #ef4444;"></i>
                                        <h3 class="mt-3 mb-0">{{ $user->likes->count() }}</h3>
                                        <p class="text-muted m-0">Total Likes</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center p-4"
                                        style="background: var(--secondary-color); border-radius: 12px;">
                                        <i class="bi bi-chat-dots-fill" style="font-size: 48px; color: #10b981;"></i>
                                        <h3 class="mt-3 mb-0">{{ $user->comments->count() }}</h3>
                                        <p class="text-muted m-0">Total Comments</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 p-4" style="background: var(--secondary-color); border-radius: 12px;">
                                <h4 class="mb-3">Recent Activity</h4>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Member Since</span>
                                    <strong>{{ Carbon::parse($user->created_at)->format('M d, Y') }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Last Post</span>
                                    <strong>
                                        @if ($user->posts->count() > 0)
                                            {{ Carbon::parse($user->posts->first()->published_at)->diffForHumans() }}
                                        @else
                                            No posts yet
                                        @endif
                                    </strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Account Status</span>
                                    <strong class="text-success">
                                        <i class="bi bi-check-circle-fill me-1"></i>
                                        Active
                                    </strong>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (($profile ?? '') == 'about')
                        <div class="info-card">
                            <h2 class="card-title">About {{ $user->name }}</h2>

                            <div class="mb-4">
                                <h5 class="text-muted mb-2">Bio</h5>
                                <p>{{ $user->bio ?? 'No bio added yet.' }}</p>
                            </div>

                            <div class="mb-4">
                                <h5 class="text-muted mb-2">Joined</h5>
                                <p>{{ Carbon::parse($user->created_at)->format('F d, Y') }}</p>
                            </div>

                            <div class="mb-4">
                                <h5 class="text-muted mb-2">Email</h5>
                                <p>{{ $user->email }}</p>
                            </div>

                            <div>
                                <h5 class="text-muted mb-2">Verification Status</h5>
                                @if ($user->is_verified)
                                    <span class="badge bg-primary">
                                        <i class="bi bi-patch-check-fill me-1"></i>
                                        Verified User
                                    </span>
                                @endif
                                @if ($user->email_verified_at)
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle-fill me-1"></i>
                                        Email Verified
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            @if ($section == 'posts')
                <div class="content-section">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="m-0">My Posts</h2>
                        <a href="{{ route('posts.create') }}" class="btn-primary">
                            <i class="bi bi-plus-lg me-2"></i>
                            Create Post
                        </a>
                    </div>

                    @if ($user->posts->count() > 0)
                        <div class="posts-grid">
                            @foreach ($user->posts as $post)
                                <article class="post-card">
                                    <a href="{{ route('posts.show', $post->id) }}">
                                        <img src="{{ Str::startsWith($post->image, ['http://', 'https://']) ? $post->image : asset('storage/' . $post->image) }}"
                                            class="post-image" alt="{{ $post->title }}">
                                    </a>
                                    <div class="post-content">
                                        <div class="post-meta">
                                            <span>{{ $post->user->name }}</span>
                                            <span>•</span>
                                            <span>{{ Carbon::parse($post->published_at)->format('M d, Y') }}</span>
                                        </div>
                                        <h3 class="post-title">
                                            <a href="{{ route('posts.show', $post->id) }}"
                                                class="text-decoration-none text-dark">
                                                {{ $post->title }}
                                            </a>
                                        </h3>
                                        <p class="post-description">{{ Str::limit($post->description, 100) }}</p>
                                        <span class="post-category">{{ $post->category->name }}</span>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="bi bi-card-text"></i>
                            <h3>No posts yet</h3>
                            <p>Start sharing your thoughts with the world!</p>
                            <a href="{{ route('posts.create') }}" class="btn-primary mt-3">
                                Create Your First Post
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            @if ($section == 'notifications')
                <div class="notification-list">
                    @if ($user->notifications->count() > 0)
                        <div class="d-flex justify-content-between align-items-center p-4 border-bottom">
                            <h2 class="m-0">Notifications</h2>
                            <form action="{{ route('notifications.markAllRead') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-check-all me-1"></i>
                                    Mark all as read
                                </button>
                            </form>
                        </div>

                        @foreach ($user->notifications as $notification)
                            <div class="notification-item {{ $notification->read_at ? '' : 'unread' }}">
                                <a href="{{ $notification->data['url'] }}?n={{ $notification->id }}"
                                    class="text-decoration-none">
                                    <div class="notification-content">
                                        <a href="{{ route('user.profile', $notification->data['userId']) }}">
                                            @if ($notification->data['creatorImage'])
                                                <img src="{{ asset('storage/' . $notification->data['creatorImage']) }}"
                                                    class="notification-avatar" alt="">
                                            @else
                                                <div class="notification-avatar avatar-default"
                                                    style="width: 48px; height: 48px; font-size: 16px;">
                                                    {{ strtoupper(substr($notification->data['title'], 0, 2)) }}
                                                </div>
                                            @endif
                                        </a>
                                        <div class="notification-body">
                                            <div class="notification-header">
                                                <span class="notification-name">{{ $notification->data['title'] }}</span>
                                                <span class="notification-type">{{ $notification->data['type'] }}</span>
                                                <span class="notification-time">
                                                    {{ Carbon::parse($notification->data['created_at'])->diffForHumans() }}
                                                </span>
                                                @if (!$notification->read_at)
                                                    <span class="notification-badge">New</span>
                                                @endif
                                            </div>
                                            <p class="notification-message">
                                                @if ($notification->type == 'App\Notifications\NewLikeNotification')
                                                    Liked your post
                                                @elseif($notification->type == 'App\Notifications\NewCommentNotification')
                                                    Commented on your post
                                                @endif
                                                "{{ $notification->data['message'] }}"
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="bi bi-bell"></i>
                            <h3>No notifications</h3>
                            <p>You're all caught up! Check back later for updates.</p>
                        </div>
                    @endif
                </div>
            @endif

            @if ($section == 'settings')
                <div class="content-section">
                    <h2 class="mb-4">Settings</h2>

                    <div class="info-card">
                        <h3 class="card-title">Privacy & Security</h3>
                        <div class="settings-grid">
                            <div class="setting-item">
                                <div class="setting-info">
                                    <h4>Email Notifications</h4>
                                    <p>Receive email notifications for new activity</p>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" checked>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>

                            <div class="setting-item">
                                <div class="setting-info">
                                    <h4>Public Profile</h4>
                                    <p>Make your profile visible to everyone</p>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" checked>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>

                            <div class="setting-item">
                                <div class="setting-info">
                                    <h4>Show Email</h4>
                                    <p>Display your email on your public profile</p>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox">
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="info-card">
                        <h3 class="card-title">Danger Zone</h3>
                        <div class="alert-box"
                            style="background: #fee2e2; border-left-color: var(--danger-color); color: #991b1b;">
                            <i class="bi bi-exclamation-triangle-fill alert-icon"></i>
                            <div class="flex-fill">
                                <strong>Delete Account</strong>
                                <p class="m-0">Once you delete your account, there is no going back.</p>
                            </div>
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                Delete Account
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </main>
    </div>

    <!-- Change Profile Picture Modal -->
    <div class="modal fade" id="changeProfile" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Profile Picture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('user.edit.photo', $user) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Choose Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                            @error('image')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="alert alert-info">
                            <small><i class="bi bi-info-circle me-1"></i> Recommended size: 400x400px</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Change Cover Photo Modal -->
    <div class="modal fade" id="changeProfileCover" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Cover Photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('user.edit.cover', $user) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Choose Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                            @error('image')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="alert alert-info">
                            <small><i class="bi bi-info-circle me-1"></i> Recommended size: 1500x500px</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-danger">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Delete Account
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete your account? This action cannot be undone.</p>
                    <p class="text-danger"><strong>All your posts, comments, and data will be permanently deleted.</strong>
                    </p>
                    <form action="{{ route('user.delete', $user->id) }}" method="POST" id="deleteAccountForm">
                        @csrf
                        @method('DELETE')
                        <div class="mb-3">
                            <label class="form-label">Type <strong>DELETE</strong> to confirm</label>
                            <input type="text" class="form-control" id="deleteConfirm" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="confirmDelete()">Delete Account</button>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', () => {
        @if (session('status1'))
            showToast('{{ session('status1') }}', 'success');
        @endif

        @if (session('status2'))
            showToast('{{ session('status2') }}', 'error');
        @endif

        function showToast(message, type = 'success') {
            const container = document.getElementById("toastContainer");
            console.log(container);

            const toast = document.createElement('div');
            toast.className = `custom-toast ${type}`;

            const icon = type === 'success' ? 'check-circle-fill' :
                type === 'error' ? 'x-circle-fill' :
                'exclamation-triangle-fill';

            toast.innerHTML = `
            <i class="bi bi-${icon}" style="font-size: 20px; color: var(--${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'warning'}-color);"></i>
            <span>${message}</span>
        `;

            container.append(toast);

            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease-out forwards';
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }

        // Character Counter for Bio
        const bioTextarea = document.getElementById('bio');
        const charCount = document.getElementById('charCount');

        if (bioTextarea && charCount) {
            bioTextarea.addEventListener('input', function() {
                charCount.textContent = this.value.length;
            });
        }

        // Delete Account Confirmation
        function confirmDelete() {
            const input = document.getElementById('deleteConfirm');
            if (input.value === 'DELETE') {
                document.getElementById('deleteAccountForm').submit();
            } else {
                alert('Please type DELETE to confirm');
            }
        }

        // Auto-hide old notification popups
        setTimeout(() => {
            const notifications = document.querySelectorAll('#notification');
            notifications.forEach(n => n.style.display = 'none');
        }, 5000);
    });
</script>

<style>
    @keyframes slideOut {
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
</style>





{{-- @php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
    //dd($user->notifications[0]->data['userId']);
@endphp
@extends('layouts.app')
@section('title')
    My Profile
@endsection
@section('content')
    <div class="position-relative">
        @if (session('status1'))
            <div id="notification" class="notification-pop-up">
                <span id="notification-text">{{ session('status1') }}</span>
            </div>
        @endif
        @if (session('status2'))
            <div id="notification" class="notification-pop-up2">
                <span id="notification-text2">{{ session('status2') }}</span>
            </div>
        @endif
        <div class="row">
            <ul class="dashboard-menu col-md-3 d-flex flex-column bg-light m-0 py-5 bg-light">
                <a href="{{ route('user.dashboard') }}?section=profile">
                    <li class="fs-5 d-flex gap-2 justify-content-start px-5 dash-item">
                        <i class="bi bi-person"></i>
                        My Profile
                    </li>
                </a>
                <a href="{{ route('user.dashboard') }}?section=posts">
                    <li class="fs-5 d-flex gap-2 justify-content-start px-5 dash-item">
                        <i class="bi bi-card-text"></i>
                        Posts
                    </li>
                </a>
                <a href="{{ route('user.dashboard') }}?section=notifications">
                    <li class="fs-5 d-flex gap-2 justify-content-start px-5 dash-item">
                        <i class="bi bi-bell"></i>
                        Notifications
                    </li>
                </a>
                <a href="{{ route('user.dashboard') }}?section=settings">
                    <li class="fs-5 d-flex gap-2 justify-content-start px-5 dash-item">
                        <i class="bi bi-gear"></i>
                        Settings
                    </li>
                </a>
            </ul>
            <div class="content col-md-9 bg-white mx-auto p-0" style="min-height: 700px">
                @if ($section == 'profile')
                    <div class="position-relative">
                        <div class="position-relative profile-cover-wrapper">
                            <button class="border-0 m-0 p-0 bg-transparent" data-bs-toggle="modal"
                                data-bs-target="#changeProfileCover">
                                @if ($user->image2)
                                    <img src="{{ asset('storage/' . $user->image2) }}" class="profile-cover" alt="...">
                                @else
                                    <div class="avatar-cover profile-cover">
                                        <span class="fs-2">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </span>
                                    </div>
                                @endif
                                <label for="photoInput" class="edit-overlay-cover">
                                    <i class="bi bi-pencil"></i>
                                </label>
                            </button>
                        </div>
                        <div class="profile-img-wrapper">
                            <button class="border-0 bg-transparent" data-bs-toggle="modal" data-bs-target="#changeProfile">
                                @if ($user->image)
                                    <img src="{{ asset('storage/' . $user->image) }}" class="profile-img">
                                @else
                                    <div class="avatar-circle profile-img">
                                        <span class="fs-2">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </span>
                                    </div>
                                @endif
                                <label for="photoInput" class="edit-overlay">
                                    <i class="bi bi-pencil"></i>
                                </label>
                            </button>
                        </div>
                    </div>
                    <ul class="list-unstyled m-0 py-4 d-flex justify-content-center gap-5">
                        <li><a href="{{ route('user.dashboard') }}?section=profile&profile=account"
                                class="link-animation">Account</a></li>
                        <li><a href="{{ route('user.dashboard') }}?section=profile&profile=statistics"
                                class="link-animation">Statistics</a></li>
                        <li><a href="{{ route('user.dashboard') }}?section=profile&profile=account"
                                class="link-animation">About</a></li>
                    </ul>
                    <div class="container mb-5 px-5">
                        <p class="d-flex display-4 fw-semibold m-0 mb-4" style="margin-top:-10px !important">
                            {{ $user->name }}
                            @if ($user->is_verified)
                                <i class="bi bi-patch-check-fill text-primary fs-2 ms-2 my-auto"></i>
                            @endif
                        </p>
                        <div class="bg-light rounded-4 p-4 mt-3 me-2">
                            @if ($profile == 'account')
                                <h2 class="mx-auto mb-4">Account Details</h2>
                                <form action="{{ route('user.update', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="card-body mb-3">
                                        <label for="exampleInputEmail1" class="form-label fw-semibold">Email address</label>
                                        <input type="email" name="email" value="{{ $user->email }}"
                                            class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                                    </div>
                                    <div class="card-body mb-3">
                                        <label for="exampleInputEmail1" class="form-label fw-semibold">Name</label>
                                        <input type="text" name="name" value="{{ $user->name }}"
                                            class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                                    </div>
                                    <div class="card-body mb-3">
                                        <label for="exampleInputEmail1" class="form-label fw-semibold">Bio</label>
                                        <textarea name="bio" id="bio" class="form-control @error('bio') is-invalid @enderror" rows="3"
                                            maxlength="160" placeholder="Write something about yourself...">{{ old('bio', $user->bio ?? '') }}</textarea>
                                    </div>
                                    <x-form-button>Edit Profile</x-form-button>
                                </form>
                                <hr class="my-5">
                                <div class="card-body mb-3">
                                    <h2 class="mx-auto mb-4">Email Verification</h2>
                                    <div class="d-flex align-items-center mt-5">
                                        @if (!$user->email_verified_at)
                                            <i class="bi bi-exclamation-triangle-fill fs-2 text-warning"></i>
                                            <h5 class="m-0 ms-3 text-warning">Your Email Not Verified!</h5>
                                            <a href="{{ route('email.show') }}"
                                                class="btn ms-auto p-2 ps-4 pe-4 auth-btn2">Verify</a>
                                        @else
                                            <i class="bi bi-check-circle-fill text-success fs-3"></i>
                                            <p class="m-0 ms-3 text-dark">Your Email is Verified!</p>
                                            <button class="btn ms-auto p-2 ps-4 pe-4 auth-btn2" disabled>Verify</button>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            @if ($profile == 'statistics')
                                <h2 class="mx-auto mb-4">Statistics</h2>
                                <div class="d-flex flex-column gap-2 mb-3">
                                    <span>
                                        <span class="fw-semibold">
                                            Total posts
                                        </span>
                                        {{ $user->posts->count() }}
                                    </span>
                                    <span>
                                        <span class="fw-semibold">
                                            Total Likes
                                        </span>
                                        {{ $user->likes->count() }}
                                    </span>
                                    <span>
                                        <span class="fw-semibold">
                                            Total Comments
                                        </span>
                                        {{ $user->comments->count() }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
                @if ($section == 'posts')
                    <div class="container p-5">
                        <div class="row g-5">
                            @foreach ($user->posts as $post)
                                <div class="col-md-4">
                                    <div class="card h-150 border-0">
                                        <a href="{{ route('posts.show', $post->id) }}" class="img-animation">
                                            <img src="{{ Str::startsWith($post->image, ['http://', 'https://']) ? $post->image : asset('storage/' . $post->image) }}"
                                                class="card-img-top img-fluid" alt="{{ $post->title }}">
                                        </a>
                                        <div class="card-body ps-0 mt-2 pe-0" id="{{ $post->id }}">
                                            <h6 class="fw-semibold">
                                                {{ $post->user->name }} •
                                                @php
                                                    $datetime = Carbon::parse($post->published_at);
                                                    echo $datetime->format('d M Y');
                                                @endphp
                                            </h6>
                                            <div class="position-relative mb-3">
                                                <div style="max-width: 90%">
                                                    <span class="card-title fs-4 fw-bold"><a
                                                            href="{{ route('posts.show', $post->id) }}"
                                                            class="link-animation text-decoration-none">{{ $post->title }}</a></span>
                                                </div>
                                                <i
                                                    class="bi bi-arrow-up-right position-absolute top-0 end-0 mt-1 fw-bolder fs-4"></i>
                                            </div>
                                            <h6 class="card-title text-muted fw-normal mb-4">
                                                {{ Str::limit($post->description, 100) }} </h6>
                                            <h6 class="card-title fw-normal d-inline border border-2 border-dark rounded-5 fw-semibold"
                                                style="padding: 2px 15px;">{{ Str::limit($post->category->name, 18) }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                @if ($section == 'notifications')
                    <div class="">
                        @if (!$user->notifications->isEmpty())
                            @foreach ($user->notifications as $notification)
                                <li class="dropdown-item py-4 notification-animation p-4">
                                    <div class="d-flex gap-3 justify-content-start w-100">
                                        <a href="{{ route('user.profile', $notification->data['userId']) }}"
                                            class="text-decoration-none">
                                            @if ($notification->data['creatorImage'])
                                                <div class="avatar-circle">
                                                    <img src="{{ asset('storage/' . $notification->data['creatorImage']) }}"
                                                        class="rounded-circle" width="40" height="40"
                                                        alt="">
                                                </div>
                                            @else
                                                <div class="avatar-circle">
                                                    {{ strtoupper(substr($notification->data['title'], 0, 2)) }}
                                                </div>
                                            @endif
                                        </a>
                                        <a href="{{ $notification->data['url'] }}?n={{ $notification->id }}"
                                            class="text-muted text-decoration-none">
                                            <div>
                                                <div class="d-flex gap-4">
                                                    <span
                                                        class="text-decoration-none text-dark fw-bold">{{ $notification->data['title'] }}</span>
                                                    <span
                                                        class="fw-semibold text-danger">{{ $notification->data['type'] }}</span>
                                                    <span class="text-muted m-0">
                                                        {{ Carbon::parse($notification->data['created_at'])->format('d M Y') }}
                                                    </span>
                                                    @if ($notification->read_at != null)
                                                        <span class="text-primary">
                                                            {{ 'Viewed' }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="fw-light">
                                                    @if ($notification->type == 'App\Notifications\NewLikeNotification')
                                                        <span>
                                                            {{ 'Liked Your Post' }}
                                                        </span>
                                                    @endif
                                                    {{ "\"" . $notification->data['message'] . "\"" }}
                                                </p>
                                            </div>
                                    </div>
                                </li>
                                </a>
                            @endforeach
                        @else
                            <div class="text-center">
                                No Notifications
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade mt-5" id="changeProfile">
        <div class="modal-dialog">
            <div class="modal-content bg-transparent">
                <div class="container card glass p-4 shadow" style="max-width: 520px;">
                    <form action="{{ route('user.edit.photo', $user) }}" method="POST" class="form"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="container d-flex flex-column justify-content-center align-items-center p-5">
                            <input type="file" name="image" class="form-control">
                            @error('image')
                                <p class="text-danger">
                                    {{ $message }}
                                </p>
                            @enderror
                            <x-form-button class="w-100">
                                Save
                            </x-form-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade mt-5" id="changeProfileCover">
        <div class="modal-dialog">
            <div class="modal-content bg-transparent">
                <div class="container card glass p-4 shadow" style="max-width: 520px;">
                    <form action="{{ route('user.edit.cover', $user) }}" method="POST" class="form"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="container d-flex flex-column justify-content-center align-items-center p-5">
                            <input type="file" name="image" class="form-control">
                            @error('image')
                                <p class="text-danger">
                                    {{ $message }}
                                </p>
                            @enderror
                            <x-form-button class="w-100">
                                Save
                            </x-form-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection --}}
