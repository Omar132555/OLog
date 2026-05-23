<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewFollowerNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $postTitle;
    public $postId;
    public $follower;
    public $followerImage;
    public $created_at;
    public $follower_id;
    public $objectId;
    /**
     * Create a new notification instance.
     */
    public function __construct($postTitle, $follower, $postId, $followerImage, $created_at, $follower_id, $objectId)
    { 
        $this->follower = $follower;
        $this->postId = $postId;
        $this->postTitle = $postTitle;
        $this->followerImage = $followerImage;
        $this->created_at = $created_at;
        $this->follower_id = $follower_id;
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

    public function toDataBase($notifiable)
    {
        return[
            'title' => $this->follower,
            'message'=> 'followed You',
            'creatorImage'=> $this->followerImage,
            'type' => 'New Follower!',
            'created_at' => Carbon::parse($this->created_at)->format('d M Y'),
            'postId' => $this->postId,
            'url'=>route('user.profile', $this->follower_id),
            'userId'=>$this->follower_id,
            'objectId'=>$this->objectId,
            'messageType' => 'New Follower'
        ];
    }
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => $this->follower,
            'message'=> 'followed You',
            'creatorImage'=> $this->followerImage,
            'type' => 'New Follower!',
            'typeof' => 'New Follower!',
            'created_at' => Carbon::parse($this->created_at)->format('d M Y'),
            'postId' => $this->postId,
            'url'=>route('user.profile', $this->follower_id),
            'userId'=>$this->objectId,
            'objectId'=>$this->objectId,
            'messageType' => $this->follower
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
