<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;

class FriendsController extends Controller
{
    public function getRandomFour(User $user)
    {
        $friends = $user->friends->shuffle()->take(6);

        foreach($friends as $f){
            $f->profile = Profile::where('user_id', $f->id)->first();
        }

        return $friends;
    }
}
