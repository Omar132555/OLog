<?php

namespace App\Http\Controllers;

use App\Events\NotificationCounterEvent;
use App\Http\Requests\CreateCommentRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Notifications\NewCommentNotification;
use Carbon\Carbon;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(CreateCommentRequest $request)
    {
        $comment = Comment::create($request->validated());
        $post = Post::find($request->post_id);
        if ($post->user->id != Auth::user()->id) {
            $count = $post->user->unreadNotifications()->count();
            $count++;
            $post->user->notify(new NewCommentNotification($post->title, $comment->user->name, $post->id, $comment->user->image, $comment->created_at, $post->user->id, $comment->id));
            event(new NotificationCounterEvent(
                $count,
                $post->user->id
            ));
        }

        return response()->json([
            'status' => 'success',
            'id' => $comment->id,
            'body' => $comment->body,
            'image' => $comment->user->image,
            'name' => $comment->user->name,
            'date' => Carbon::parse($comment->created_at)->format('d M Y'),
            'userId' => $comment->user->id
        ]);
    }

    public function destroy(Comment $comment)
    {
        DatabaseNotification::where('data->objectId', $comment->id)->delete();
        $comment->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }
}
