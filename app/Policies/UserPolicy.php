<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the profile.
     *
     * @param User $user
     * @param array|User $args
     * @return bool
     */
    public function update(User $user, array|User $args): bool
    {
        $model = $this->getModel($args);
        return $user->id === $model->id || $user->role === User::ROLE_ADMIN;
    }

    public function delete(User $user, array $args): bool
    {
        $model = $this->getModel($args);
        return $user->id === $model->id || $user->role === User::ROLE_ADMIN;
    }
}
