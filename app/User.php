<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\Friend;
use App\Traits\BlockUser;

class User extends Authenticatable
{
    use Notifiable;
    use Friend; // allowing to call $user->friends
    use BlockUser; // allowing to call $user->blocked_friends


    protected $fillable = ['name', 'email', 'username', 'password',];

    protected $hidden = ['password', 'remember_token',];

    protected $casts = ['email_verified_at' => 'datetime',];


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
    public function posts()
    {
        return $this->hasMany(Post::class)->orderBy('created_at', 'DESC');
    }

    /**
     * returns all profiles that this user follows
    */
    public function following()
    {
        return $this->belongsToMany(Profile::class);
    }

    /**
     * returns all posts that this user liked
    */
    public function liking()
    {
        return $this->belongsToMany(Post::class);
    }

    /**
     * returns the profile of this user
    */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * returns messages that this user sent
    */
    public function messages()
    {
      return $this->hasMany(Message::class);
    }

    /**
     * returns pending friend requests that this user recieved
    */
    public function friend_requests()
    {
        return $this->hasMany(Friendship::class, 'second_user')->where('status', 'pending');
    }

    /**
     * returns pending friend requests that this user sent
    */
    public function sent_friend_requests()
    {
        return $this->hasMany(Friendship::class, 'first_user')->where('status', 'pending');
    }


}
