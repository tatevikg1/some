<?php

namespace App\Http\Controllers;

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

    public function index()
    {
        $title = 'Friends';
        $user = auth()->user();
        
        $this->markAsRead("App\Notifications\NewFriendRequest");

        $users = $user->friends;
        foreach($users as $u){
            $u->friendship = Friendship::recordReletedTo($u);
        }

        $friend_requests = $user->friend_requests;

        foreach($friend_requests as $f){
            $f->creator = User::find($f->acted_user);
        } 

        return view('profiles.index', compact('users', 'friend_requests', 'title'));
    }

    public function send_friend_request(User $user)
    {
        $friendship = Friendship::create([
            'first_user' => auth()->user()->id,
            'second_user' => $user->id,
            'acted_user' => auth()->user()->id,
            'status' => 'pending',
        ]);
        
        $noti = new NewFriendRequest($friendship);
        $user->notify($noti);
        broadcast($noti);

        return $friendship;
    }

    public function confirm_friend_request(Friendship $friendship)
    {            
        $friendship->update([
            'status' => 'confirmed',
            'acted_user' => auth()->user()->id,
        ]);

        $this->follow_each_other($friendship);

        return $friendship;
    }

    public function delete_friend_request(Friendship $friendship)
    {
        $this->deleteNotification($friendship->id);
        
        return $friendship->delete();;
    }

    public function block(User $user)
    {
        $friendship = DB::table('friendships')
            ->where([['first_user', auth()->user()->id], ['second_user', $user->id]])
            ->orWhere([['first_user', $user->id], ['second_user', auth()->user()->id ]])
            ->first();

        $friendship->update(['status' => 'blocked']);

        return $friendship;
    }

    public function markAsRead()
    {
        // mark read new friend request notifications of user
        $user = auth()->user();
        $user->unreadNotifications
            ->where('type', "App\Notifications\NewFriendRequest")
            ->markAsRead();
        return true;
    }


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
