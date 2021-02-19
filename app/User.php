<?php

namespace App;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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


    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            $user->profile()->create([
                'title' => $user->username,
            ]);

            // Mail::to($user->email)->send(new NewUserWelcomeMail());
        });
    }


    public function posts()
    {
        return $this->hasMany(Post::class)->orderBy('created_at', 'DESC');
    }

    public function following()
    {
        return $this->belongsToMany(Profile::class);
    }

    public function liking()
    {
        return $this->belongsToMany(Post::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function messages()
    {
      return $this->hasMany(Message::class);
    }

    public function friend_requests()
    {
        return $this->hasMany(Friendship::class, 'second_user')->where('status', 'pending');
    }

}
