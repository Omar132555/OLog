<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function likedPosts()
    {
        return $this->belongsToMany(Post::class, 'likes');
    }

    public function following()
    {
        return $this->belongsToMany(
            User::class,    // type of the returned entity
            'follows',      // table the contains the relational data
            'follower_id',  // id of the provided user (follower)
            'following_id'  // id of returned users
        );  // return the users that this user follows
    }

    public function followers()
    {
        return $this->belongsToMany(
            User::class,    // type of the returned entity
            'follows',      // table the contains the relational data
            'following_id',  // id of the provided user (follower)
            'follower_id'  // id of returned users
        );  // return the users that this user follows
    }

    public function savedPosts()
    {
        return $this->belongsToMany(Post::class, 'saved_posts');
    }

    public function isFollowing($id)
    {
        return Auth::user()
            ->following()
            ->where('following_id', $id)
            ->exists();
    }
}
