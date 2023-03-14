<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\Models\User;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
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

        $allUsers = User::where('id', '!=', $authUser->getAuthIdentifier())->get();
        $users = $allUsers->reject(function ($user) {
            // return users that has no friendship record related with auth user
            return Friendship::recordRelatedTo($user);

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

    /**
     * @param User $user
     * @return Application|Factory|View
     */
    public function show(User $user)
    {
        $postCount = Cache::remember(
            'count.posts.' . $user->id,
            now()->addSeconds(30),
            function () use ($user) {
                return $user->posts()->count();
            });

        $followersCount = Cache::remember(
            'count.followers.' . $user->id,
            now()->addSeconds(30),
            function () use ($user) {
                return $user->profile->followers()->count();
            });

        $followingCount = Cache::remember(
            'count.following.' . $user->id,
            now()->addSeconds(30),
            function () use ($user) {
                return $user->following()->count();
            });

        // if the user is not authenticated he is not following the profile he is visiting
        if(!Auth::check()){
            $follows = false;
            return view('profiles.show', compact(
                'user',  'follows',
                'postCount',  'followersCount', 'followingCount'
            ));
        }

        $follows = (auth()->user()) ? auth()->user()->following->contains($user->id) : false;

        if(auth()->user()->getAuthIdentifier() == $user->id){
            return view('profiles.show', compact(
                'user',  'follows',
                'postCount',  'followersCount', 'followingCount'
            ));
        }

        $friendship = Friendship::recordRelatedTo($user);

        return view('profiles.show', compact(
            'user', 'follows',
            'postCount', 'followersCount',  'followingCount',
            'friendship'
        ));
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user->profile);

        return view('profiles.edit', compact('user'));
    }

    /**
     * @throws AuthorizationException
     */
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

        auth()->user()->profile()->update(array_merge(
            $data,
            $imageArray ?? []
        ));

        return redirect("/profile/{$user->id}");
    }

    /**
     * @throws Exception
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect('/register');
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function find(Request $request)
    {
        $title = 'Search result';
        $data = $request->validate([
            'username' => 'required|min:2',
        ]);

        $users = User::where('name', 'like', '%'. $data['username'] .'%')->get();

        return view('profiles.index', compact('users', 'title'));
    }
}
