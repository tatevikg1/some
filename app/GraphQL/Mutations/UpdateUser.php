<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use League\OAuth2\Server\Exception\OAuthServerException;

class UpdateUser
{
    /**
     * @throws OAuthServerException
     */
    public function __invoke($root, array $args)
    {
        $user = User::find($args['id']);
        /** @var User $authUser */
        $authUser = auth()->user();
        if (isset( $args['role'])) {
            if ($authUser->role === User::ROLE_ADMIN && $user->id === $authUser->id) {
                $args['role'] = User::ROLE_ADMIN;
            } elseif ($authUser->role !== User::ROLE_ADMIN) {
                $args['role'] = User::ROLE_USER;
            }
        }

        $user->update($args);

        return $user;
    }
}
