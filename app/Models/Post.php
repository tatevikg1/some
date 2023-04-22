<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property User $user
 * @property User[] $likers
*/
class Post extends BaseModel
{
    protected $guarded = [];

    /**
     * returns the user who created the post
    */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * returns users who liked the post
    */
    public function likers(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

}
