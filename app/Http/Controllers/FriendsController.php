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
        
        $this->updateNotifications($user);

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
        
        $user->notify(new NewFriendRequest($friendship));

        return $friendship;
    }

    public function confirm_friend_request(Friendship $friendship)
    {            
        $friendship->update([
            'status' => 'confirmed',
            'acted_user' => auth()->user()->id,
        ]);

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

    public function updateNotifications(User $user)
    {
        $user->unreadNotifications->markAsRead();
        return true;
    }

    protected function deleteNotification($friendship_id)
    {
        DB::table('notifications')->where('data', '{"id":'.$friendship_id.'}')->delete();
    }

}
