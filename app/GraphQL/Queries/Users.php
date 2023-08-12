<?php

namespace App\GraphQL\Queries;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class Users
{
    private const PER_PAGE = 5;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args): LengthAwarePaginator
    {
        $query = User::query();

        if (isset($args['name'])) {
            $query->where('name', 'LIKE', '%' . $args['name'] . '%');
        }

        if (isset($args['email'])) {
            $query->where('email', $args['email']);
        }

        if (isset($args['id_gt'])) {
            $query->where('id', '>', $args['id_gt']);
        }

        if (isset($args['id'])) {
            $query->where('id', '=', $args['id']);
        }

        if (isset($args['name_like'])) {
            $query->where('name', 'like', "%" . $args['name_like'] . "%");
        }

        return $query->paginate($args['per_page'] ?? self::PER_PAGE);
    }
}
