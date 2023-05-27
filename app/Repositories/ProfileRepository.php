<?php

namespace App\Repositories;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;
use Throwable;

class ProfileRepository
{
    public function follow_each_other_profile(Friendship $friendship)
    {
        $user1 = User::find($friendship->first_user);
        $user2 = User::find($friendship->second_user);

        $user1->following()->toggle($user2->profile);
        $user2->following()->toggle($user1->profile);
    }

    public function index(): array
    {
        /** @var User $authUser */
        $authUser = auth()->user();
        $allUsers = User::where('id', '!=', $authUser->getAuthIdentifier())->get();
        $users = $allUsers->reject(function ($user) {
            // return users that has no friendship record related with auth user
            return Friendship::recordRelatedTo($user);
        });

        $sentFriendRequest = $authUser->sent_friend_requests;
        foreach($sentFriendRequest as $f){
            $f->creator = User::find($f->second_user);
        }

        $friend_requests = $authUser->friend_requests;
        foreach($friend_requests as $f){
            $f->creator = User::find($f->acted_user);
        }

        $friend_requests = $sentFriendRequest->merge($friend_requests);

        return [$users, $friend_requests];
    }

    public function show(User $user): array
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

        if (!Auth::check()) {
            return [$postCount, $followersCount, $followingCount, null, null];
        }

        $authUser = User::find(auth()->user()->getAuthIdentifier());
        $follows = $authUser ? $authUser->following->contains($user->id) : false;

        if ($authUser->id === $user->id) {
            return [$postCount, $followersCount, $followingCount, $follows, null];
        }

        $friendship = Friendship::recordRelatedTo($user);

        return [$postCount, $followersCount, $followingCount, $follows, $friendship];
    }

    public function update(User $user, mixed $data): void
    {
        if (request('image')) {
            $imagePath = request('image')->store('profile', 'public');

            try {
                $image = Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1000);
                $image->save();
            } catch (Throwable $throwable) {

            }

            $imageArray = ['image' => $imagePath];
        }

        $user->profile()->update(array_merge(
            $data,
            $imageArray ?? []
        ));
    }
}
