<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = [];

    /**
     * returns the user who created the post
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * returns users who liked the post
    */
    public function likers()
    {
        return $this->belongsToMany(User::class);
    }

}
