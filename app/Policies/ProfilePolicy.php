<?php

namespace App\Policies;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProfilePolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can update the profile.
     *
     * @param User $user
     * @param Profile $profile
     * @return bool
     */
    public function update(User $user, Profile $profile): bool
    {
        return $user->id == $profile->user_id;
    }

}
