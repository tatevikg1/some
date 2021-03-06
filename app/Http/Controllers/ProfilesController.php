<?php

namespace App\Http\Controllers;

use App\Friendship;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;


class ProfilesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show']]);
    }

    public function index()
    {
        $title = 'Find Friends';
        $authUser = auth()->user();

        $allUsers = User::where('id', '!=', $authUser->id)->get();
        $users = $allUsers->reject(function ($user) {
            // return users that has no friendship record related with auth user
            return Friendship::recordReletedTo($user);
            
            // // return all users except auth users friends
            // return Auth::user()->friends->contains($user->id);
        });

        $sent = $authUser->sent_friend_requests;
        foreach($sent as $f){
            $f->creator = User::find($f->second_user);
        } 

        $friend_requests = auth()->user()->friend_requests;
        foreach($friend_requests as $f){
            $f->creator = User::find($f->acted_user);
        } 

        $friend_requests = $sent->merge($friend_requests);
        return view('profiles.index', compact('users', 'friend_requests', 'title'));
    }

    public function show(User $user)
    {
        $postCount = Cache::remember(
            'count.posts.' . $user->id,
            now()->addSeconds(30),
            function () use ($user) {
                return $user->posts->count();
            });

        $followersCount = Cache::remember(
            'count.followers.' . $user->id,
            now()->addSeconds(30),
            function () use ($user) {
                return $user->profile->followers->count();
            });

        $followingCount = Cache::remember(
            'count.following.' . $user->id,
            now()->addSeconds(30),
            function () use ($user) {
                return $user->following->count();
            });

        // if the user is not authenticated he is not folloing the profile he is visiting
        if(!Auth::check()){
            $follows = false;
            return view('profiles.show', compact( 'user', 'follows', 'postCount', 'followersCount', 'followingCount'));
        }
        
        $follows = (auth()->user()) ? auth()->user()->following->contains($user->id) : false;

        if(auth()->user()->id == $user->id){
            return view('profiles.show', compact( 'user', 'follows', 'postCount', 'followersCount', 'followingCount'));
        }

        $friendship = Friendship::recordReletedTo($user);


        return view('profiles.show', compact( 
            'user', 'follows', 
            'postCount', 'followersCount', 'followingCount',
            'friendship'
        ));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user->profile);

        return view('profiles.edit', compact('user'));
    }

    public function update(User $user)
    {
        $this->authorize('update', $user->profile);

        $data = request()->validate([
            'title' => 'required',
            'description' => '',
            'url' => '',
            'image' => '',
        ]);

        if (request('image')) {
            $imagePath = request('image')->store('profile', 'public');

            $image = Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1000);
            $image->save();

            $imageArray = ['image' => $imagePath];
        }

        auth()->user()->profile->update(array_merge(
            $data,
            $imageArray ?? []
        ));

        return redirect("/profile/{$user->id}");
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect('/register');
    }

    public function find(Request $request)
    {
        $data = request()->validate([

            'username' => 'required',
        ]);

        $username =$request->input('username');

        $users = User::where('name', 'like', '%'.$username.'%')->get();

        return view('profiles.index', compact('users'));
    }
}
