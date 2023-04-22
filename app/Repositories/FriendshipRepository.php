<?php

namespace App\Repositories;

use App\Events\NewFriendRequestEvent;
use App\Models\Friendship;
use App\Models\User;
use App\Notifications\NewFriendRequest;
use Illuminate\Support\Facades\DB;

class FriendshipRepository
{

    public function getFriendRequestsForUser(User $user): array
    {
        $this->markAsRead();

        $friends = $user->friends->take(5);
        foreach($friends as $friend){
            $friend->friendship = Friendship::recordRelatedTo($friend);
        }

        $friendRequests = $user->friend_requests;

        foreach($friendRequests as $friendRequest){
            $friendRequest->creator = Friendship::creator($friendRequest->acted_user);
        }

        return [
            $friends,
            $friendRequests
        ];
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

    public function createFriendRequest(int $userId)
    {
        $friendship = Friendship::create([
            'first_user' => auth()->id(),
            'second_user' => $userId,
            'acted_user' => auth()->id(),
            'status' => 'pending',
        ]);

        NewFriendRequestEvent::dispatch($friendship);
        return $friendship;
    }

    public function confirmFriendRequest(Friendship $friendship): Friendship
    {
        $friendship->update([
            'status' => 'confirmed',
            'acted_user' => auth()->id(),
        ]);
        $profileRepository = new ProfileRepository();
        $profileRepository->follow_each_other_profile($friendship);

        return $friendship;
    }

    public function blockFriend(int $userId)
    {
        $friendship = DB::table('friendships')
            ->where([
                ['first_user', auth()->id()],
                ['second_user', $userId],
            ])
            ->orWhere([
                ['first_user', $userId],
                ['second_user', auth()->id()],
            ])
            ->first();

        $friendship->update(['status' => 'blocked']);
        return $friendship;
    }

}
