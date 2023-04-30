<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $type
 * @property string $token_id
 * @property array $params
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property User $user
 */
class SocialProfile extends BaseModel
{
    public const TYPE_APPLE = 1;
    public const TYPE_GOOGLE = 2;
    public const TYPE_FACEBOOK = 3;
    public const TYPE_TWITTER = 4;

    public const DRIVER_MAP = [
        self::TYPE_APPLE => 'sign-in-with-apple',
        self::TYPE_GOOGLE => 'google',
        self::TYPE_FACEBOOK => 'facebook',
        self::TYPE_TWITTER => 'twitter',
    ];

    public const REDIRECT_MAP = [
        self::TYPE_APPLE => 'apple',
        self::TYPE_GOOGLE => 'google',
        self::TYPE_FACEBOOK => 'facebook',
        self::TYPE_TWITTER => 'twitter',
    ];

    protected $table = 'social_profiles';

    protected $casts = [
        'user_id' => 'int',
        'type' => 'int',
        'params' => 'json',
    ];

    protected $fillable = [
        'user_id',
        'type',
        'token_id',
        'params',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
