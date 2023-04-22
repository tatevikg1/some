<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;

class PostController extends Controller
{
    public function getLikeCount(Post $post): int
    {
        return $post->likers()->count();
    }

    public function isLikedByUser(Post $post, User $user)
    {
        return  ($user->id) ? $user->liking->contains($post->id) : 0;
    }
}
