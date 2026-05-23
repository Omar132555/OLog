<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $postTitle;

    public $postId;

    public $postCreator;

    public $creatorImage;

    public $created_at;

    public $user_id;

    public $objectId;

    /**
     * Create a new notification instance.
     */
    public function __construct($postTitle, $postCreator, $postId, $creatorImage, $created_at, $user_id, $objectId)
    {
        $this->user_id = $user_id;
        $this->postCreator = $postCreator;
        $this->postId = $postId;
        $this->postTitle = $postTitle;
        $this->creatorImage = $creatorImage;
        $this->created_at = $created_at;
        $this->objectId = $objectId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->postCreator,
            'message' => $this->postTitle,
            'creatorImage' => $this->creatorImage,
            'type' => 'New Comment!',
            'created_at' => $this->created_at,
            'postId' => $this->postId,
            'url' => route('posts.show', $this->postId),
            'userId' => $this->user_id,
            'objectId' => $this->objectId,
            'messageType' => 'Commented on your Post',
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => $this->postCreator,
            'message' => $this->postTitle,
            'creatorImage' => $this->creatorImage,
            'type' => 'New Comment!',
            'typeof' => 'New Comment!',
            'created_at' => Carbon::parse($this->created_at)->format('d M Y'),
            'postId' => $this->postId,
            'url' => route('posts.show', $this->postId),
            'userId' => $this->user_id,
            'objectId' => $this->objectId,
            'messageType' => 'Commented on your Post',
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
