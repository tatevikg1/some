<?php

namespace App\Http\Controllers;

use App\Events\NewFriendRequestEvent;
use App\Friendship;
use App\Notifications\NewFriendRequest;
use App\User;
use Illuminate\Support\Facades\DB;

class FriendsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * shows friends and friend_requests
    */
    public function index()
    {
        $title = 'Friends';
        /** @var User $user */
        $user = auth()->user();

        $this->markAsRead();

        $users = $user->friends;
        foreach($users as $u){
            $u->friendship = Friendship::recordRelatedTo($u);
        }

        $friendRequests = $user->friend_requests;

        foreach($friendRequests as $friendRequest){
            $friendRequest->creator = User::find($friendRequest->acted_user);
        }

        return view('profiles.index', compact('users', 'friendRequests', 'title'));
    }


    public function send_friend_request(User $user)
    {
        $friendship = Friendship::create([
            'first_user' => auth()->user()->id,
            'second_user' => $user->id,
            'acted_user' => auth()->user()->id,
            'status' => 'pending',
        ]);

        NewFriendRequestEvent::dispatch($friendship);

        return $friendship;
    }

    public function confirm_friend_request(Friendship $friendship): Friendship
    {
        $friendship->update([
            'status' => 'confirmed',
            'acted_user' => auth()->user()->id,
        ]);

        $this->follow_each_other($friendship);

        return $friendship;
    }

    public function delete_friend_request(Friendship $friendship): ?bool
    {
        $this->deleteNotification($friendship->id);

        return $friendship->delete();
    }


    public function block(User $user)
    {
        $friendship = DB::table('friendships')
            ->where([
                ['first_user', auth()->user()->id],
                ['second_user', $user->id],
            ])
            ->orWhere([
                ['first_user', $user->id],
                ['second_user', auth()->user()->id],
            ])
            ->first();

        $friendship->update(['status' => 'blocked']);

        return $friendship;
    }

    /**
     * markAsRead newFriendRequest notifications of user
    */
    public function markAsRead(): bool
    {
        $user = auth()->user();
        $user->unreadNotifications
            ->where('type', NewFriendRequest::class)
            ->markAsRead();
        return true;
    }

    /**
     * deletes from the database friendship notification
    */
    protected function deleteNotification($friendship_id)
    {
        DB::table('notifications')->where('data', '{"id":'.$friendship_id.'}')->delete();
    }

    protected function follow_each_other(Friendship $friendship)
    {
        $user1 = User::find($friendship->first_user);
        $user2 = User::find($friendship->second_user);

        $user1->following()->toggle($user2->profile);
        $user2->following()->toggle($user1->profile);
    }

}
