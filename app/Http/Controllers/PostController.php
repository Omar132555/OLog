<?php

namespace App\Http\Controllers;

use App\Events\NotificationCounterEvent;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Like;
use App\Models\Post;
use App\Notifications\NewLikeNotification;
use App\Notifications\NewPostNotification;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function home()
    {
        $latest = Post::latest()->take(3)->get();
        $featured = Post::latest()->first();
        $mostPopular = Post::withCount('likes')->orderByDesc('likes_count')->take(3)->get();

        return view('Home', ['mostPopular' => $mostPopular, 'latest' => $latest, 'featured' => $featured]);
    }

    public function index(Request $request)
    {
        $allCategories = \App\Models\Category::withCount('posts')->get();
        $selectedId    = $request->get('category');
        $sort          = $request->get('sort', 'latest');
        $searchQuery   = $request->get('q');

        $query = Post::with(['user', 'category', 'likes', 'comments']);

        if ($selectedId) {
            $query->where('category_id', $selectedId);
        }

        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('title', 'like', '%' . $searchQuery . '%')
                  ->orWhere('description', 'like', '%' . $searchQuery . '%');
            });
        }

        if ($sort === 'popular') {
            $query->withCount('likes')->orderByDesc('likes_count');
        } else {
            $query->latest();
        }

        $allPosts = $query->paginate(12);

        return view('Posts.index', [
            'posts'          => $allPosts,
            'allCategories'  => $allCategories,
            'selectedId'     => $selectedId,
            'totalPostCount' => Post::count(),
        ]);
    }

    public function category(Request $request)
    {
        $allCategories = \App\Models\Category::withCount('posts')->get();
        $selectedCategoryId = $request->category;
        $selectedCategory = null;
        $sort = $request->get('sort', 'latest');
        $searchQuery = $request->get('q');

        $query = Post::with(['user', 'category', 'likes', 'comments']);

        if ($selectedCategoryId) {
            $selectedCategory = \App\Models\Category::find($selectedCategoryId);
            $query->where('category_id', $selectedCategoryId);
        }

        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('title', 'like', '%' . $searchQuery . '%')
                  ->orWhere('description', 'like', '%' . $searchQuery . '%');
            });
        }

        if ($sort === 'popular') {
            $query->withCount('likes')->orderByDesc('likes_count');
        } else {
            $query->latest();
        }

        $allPosts = $query->paginate(9);

        return view('Posts.categories', [
            'category'       => $selectedCategory,
            'posts'          => $allPosts,
            'allCategories'  => $allCategories,
            'selectedId'     => $selectedCategoryId,
            'totalPostCount' => \App\Models\Post::count(),
        ]);
    }

    public function show(Post $post, Request $request)        // route binding model
    {
        $user = Auth::user();
        $comments = $post->comments;
        if (Auth::user()) {
            $isLiked = $post->likedByUsers()->where('user_id', $user->id)->exists();
        }
        if ($request->n) {
            $notification = $user->notifications()->where('id', $request->n)->first();
            if ($notification) {
                $notification->markAsRead();
            }
        }

        return view('Posts.show', ['post' => $post, 'isLiked' => $isLiked ?? '', 'comments' => $comments ?? '']);
    }

    public function create()
    {
        return view('Posts.create');
    }

    public function store(CreatePostRequest $request)
    {
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
        }
        $baseSlug = Str::slug($request->title);
        $slug = $baseSlug;

        while (Post::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.random_int(1, 9999);
        }
        $post = Post::create([
            'title' => $request->title,
            'user_id' => Auth::user()->id,
            'status' => 'published',
            'slug' => $slug,
            'published_at' => now(),
            'category_id' => $request->categoryId,
            'description' => $request->description,
            'image' => $path ? $path : null,
        ]);
        $followers = Auth::user()->followers()->get();
        foreach ($followers as $follower) {
            $follower->notify(new NewPostNotification($request->title, Auth::user()->name, $post->id, Auth::user()->image, $post->created_at, $follower->id, $post->id));
            event(new NotificationCounterEvent(
                $follower->unreadNotifications()->count(),
                $follower->id
            ));
        }

        return response()->json([
            'redirect' => route('posts.index'),
        ]);
    }

    public function edit(Post $post)
    {
        return view('Posts.edit', ['post' => $post]);
    }

    public function update(Post $post, UpdatePostRequest $request)
    {
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($post->image);
            $path = $request->file('image')->store('posts', 'public');
        }
        if (isset($path)) {
            $post->update([
                'title' => $request->title,
                'category_id' => $request->categoryId,
                'description' => $request->description,
                'image' => $path,
            ]);
        } else {
            $post->update([
                'title' => $request->title,
                'category_id' => $request->categoryId,
                'description' => $request->description,
            ]);
        }

        return response()->json([
            'redirect' => route('posts.show', $post->id),
        ]);
    }

    public function destroy(Post $post)
    {
        DatabaseNotification::where('data->postId', $post->id)->delete();
        $post->delete();

        return response()->json([
            'redirect' => route('Home'),
        ]);
    }

    public function search(Request $request, SearchService $searchService)
    {
        return response()->json([
            $searchService->search($request->q),
        ]);
    }

    public function postLike($id, $userId)
    {
        $post = Post::whereId($id)->first();
        $result = $post->likedByUsers()->toggle($userId);
        $like = Like::latest()->first();
        $cacheKey = 'like'.md5($id);
        $is_liked = Cache::get($cacheKey, false);
        if (! empty($result['attached'])) {
            if ($post->user->id != $userId) {
                if (! $is_liked) { // false
                    $post->user->notify(new NewLikeNotification($post->title, Auth::user()->name, $post->id, Auth::user()->image, now(), $post->user->id, $like->id));
                    event(new NotificationCounterEvent(
                        $post->user->unreadNotifications()->count(),
                        $post->user->id
                    ));
                    Cache::put($cacheKey, true, 60 * 2);
                }
            }

            return response()->json([
                'status' => 'liked',
                'likes' => $post->likes->count(),
            ]);
        }

        if (! empty($result['detached'])) {
            return response()->json([
                'status' => 'unliked',
                'likes' => $post->likes->count(),
            ]);
            DatabaseNotification::where('data->objectId', $post->id)->where('type', 'App\Notifications\NewLikeNotification')->delete();
            $post->likedByUsers()->toggle($userId);
        }
    }

    public function savePost(Post $post){
        $result = Auth::user()->savedPosts()->toggle($post->id);
        if(!empty($result['attached']))
        {
            return response()->json([
                'status' => 'saved'
            ]);
            }
            if(!empty($result['detached']))
                {
            return response()->json([
                'status' => 'unsaved'
            ]);
        }
    }
}



//  Hugging face   (large lang model)