<?php

namespace App\Http\Controllers;

use App\Friendship;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;
// use Illuminate\Support\Facades\DB;


class ProfilesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(User $user)
    {
        $users = User::where('id', '!=', auth()->id())->get();
        $title = 'All users';

        return view('profiles.index', compact('users', 'title'));
    }

    public function show(User $user)
    {
        $follows = (auth()->user()) ? auth()->user()->following->contains($user->id) : false;

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

        if(auth()->user()->id == $user->id){
            return view('profiles.show', compact( 'user', 'follows', 'postCount', 'followersCount', 'followingCount'));
        }

        $friendship = Friendship::recordReletedTo($user);

        // dd($friendship);

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
