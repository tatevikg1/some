<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property User $user
 * @property string $image
 * @property string $caption
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property User[] $likers
*/
class Post extends BaseModel
{
    use HasFactory;

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
