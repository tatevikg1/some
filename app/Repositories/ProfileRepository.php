<?php

namespace App\Repositories;

use App\Friendship;
use App\User;

class ProfileRepository
{
    public function follow_each_other_profile(Friendship $friendship)
    {
        $user1 = User::find($friendship->first_user);
        $user2 = User::find($friendship->second_user);

        $user1->following()->toggle($user2->profile);
        $user2->following()->toggle($user1->profile);
    }
}
