<?php

namespace App\Models;

use App\Traits\BlockUser;
use App\Traits\Friend;
use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $username
 * @property string $device_key
 * @property Carbon $last_login_at
 * @property int $role
 * @property Carbon $deleted_at
 * @property $unreadNotifications
 * @property Profile $profile
 * @property Post[] $posts
 * @property Profile[] $following
 * @property Message[] $messages
 * @property User[] $friends
 * @property Post[] $liking
 * @property Friendship[] $friend_requests
 * @property Friendship $friendship
 * @property string $password
 * @property int $status
 * @property LoginAttempt $loginAttempt
 * @property UserSetting $userSetting
 * @property SocialProfile[] $socialProfiles
 */
class User extends BaseModel  implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail, HasFactory, HasApiTokens;
    use Notifiable, Friend, BlockUser; // allowing to call $user->blocked_friends
    use SoftDeletes;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE_NOT_CONFIRMED = 2;
    const ROLE_USER = 0;
    const ROLE_ADMIN = 1;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'device_key',
        'last_login_at',
        'status',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'deleted-at' => 'datetime',
        'role' => 'int',
        'password' => 'hashed', // todo - check if it is working OK
    ];
//    private mixed $friendsOfThisUser;
//    private mixed $thisUserFriendOf;

    /**
     * creates profile for new user
    */
    protected static function boot(): void
    {
        parent::boot();

        static::created(function ($user) {
            $user->profile()->create([
                'title' => $user->username,
            ]);
            $user->userSetting()->create();
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

    public function firstFivePosts(): HasMany
    {
        return $this->posts()->take(5);
    }

    public function loginAttempt(): HasOne
    {
        return $this->hasOne(LoginAttempt::class, 'id');
    }

    public function userSetting(): HasOne
    {
        return $this->hasOne(UserSetting::class, 'id');
    }

    public function socialProfiles(): HasMany
    {
        return $this->hasMany(SocialProfile::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeUser(Builder $query): Builder
    {
        return $query->where('role', self::ROLE_USER);
    }
}
