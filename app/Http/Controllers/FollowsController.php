<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;


class FollowsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(User $user, Request $request)
    {
        return $request->user()->following()->toggle($user->profile);
    }

}
