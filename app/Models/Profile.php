<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $user_id
 * @property string $image
 * @property User $user
 * @property User[] $followers
*/
class Profile extends BaseModel
{
    protected $guarded = [];

    public function profileImage(): string
    {
        return ($this->image) ? $this->image : 'profile/profile.jpeg';
    }

    public function getImageAttribute(?string $value = null): string
    {
        return $value ? : 'profile/profile.jpeg';
    }

    /**
     * returns users who follow this profile
    */
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * returns the user who owns the profile
    */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
