<?php

namespace App\Http\Controllers;

use App\Friendship;
use App\Repositories\FriendshipRepository;
use App\User;
use Illuminate\Support\Facades\DB;

class FriendsController extends Controller
{
    private FriendshipRepository $repository;
    public function __construct(FriendshipRepository $repository)
    {
        $this->repository = $repository;
        $this->middleware('auth');
    }

    /**
     * shows friends and friend_requests`
    */
    public function index()
    {
        $title = 'Friends';
        /** @var User $user */
        $user = auth()->user();
        list($users, $friendRequests) = $this->repository->getFriendRequestsForUser($user);

        return view('profiles.index', compact('users', 'friendRequests', 'title'));
    }

    public function send_friend_request(User $user)
    {
        return $this->repository->createFriendRequest($user->id);
    }

    public function confirm_friend_request(Friendship $friendship): Friendship
    {
        return $this->repository->confirmFriendRequest($friendship);
    }

    public function delete_friend_request(Friendship $friendship): ?bool
    {
        return $friendship->delete();
    }

    public function block(User $user)
    {
        return $this->repository->blockFriend($user->id);
    }
}
