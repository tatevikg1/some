<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Friendship;
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
        $user = auth()->user();

        $users = $user->friends;
        return view('profiles.index', compact('users'));
    }

    public function send_friend_request(User $user)
    {
        $friendship = Friendship::create([
            'first_user' => auth()->user()->id,
            'second_user' => $user->id,
            'acted_user' => auth()->user()->id,
            'status' => 'pending',
        ]);

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
        $friendship->delete();
        
        return null;
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

}
