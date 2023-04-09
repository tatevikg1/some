<?php

namespace App\Models;

use App\Traits\BlockUser;
use App\Traits\Friend;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $username
 * @property $unreadNotifications
 * @property Profile $profile
 * @property Post[] $posts
 * @property Profile[] $following
 * @property Message[] $messages
// * @property User[] $friends
 * @property Post[] $liking
 * @property Friendship[] $friend_requests
 * @property Friendship $friendship

 */
class User extends Authenticatable
{
    use Notifiable;
    use Friend; // allowing to call $user->friends
    use BlockUser; // allowing to call $user->blocked_friends

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * creates profile for new user
    */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            $user->profile()->create([
                'title' => $user->username,
            ]);
        });
    }

    /**
     * returns user's posts
    */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class)->orderBy('created_at', 'DESC');
    }

    /**
     * returns all profiles that this user follows
    */
    public function following(): BelongsToMany
    {
        return $this->belongsToMany(Profile::class);
    }

    /**
     * returns all posts that this user liked
    */
    public function liking(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }

    /**
     * returns the profile of this user
    */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * returns messages that this user sent
    */
    public function messages(): HasMany
    {
      return $this->hasMany(Message::class);
    }

    /**
     * returns pending friend requests that this user received
    */
    public function friend_requests(): HasMany
    {
        return $this->hasMany(Friendship::class, 'second_user')->where('status', 'pending');
    }

    /**
     * returns pending friend requests that this user sent
    */
    public function sent_friend_requests(): HasMany
    {
        return $this->hasMany(Friendship::class, 'first_user')->where('status', 'pending');
    }

    public function firstFivePosts()
    {
        return $this->posts()->take(5);
    }
}
