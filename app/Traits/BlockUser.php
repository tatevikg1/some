<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait BlockUser
{
	/**
	 * friendship that this user started but now blocked
	 */
	protected function friendsOfThisUserBlocked(): BelongsToMany
    {
		return $this->belongsToMany(User::class, 'friendships', 'first_user', 'second_user')
					->withPivot('status', 'acted_user')
					->wherePivot('status', 'blocked')
					->wherePivot('acted_user', 'first_user');
	}

	/*
	 * friendship that this user was asked for but now blocked
	 */
	protected function thisUserFriendOfBlocked(): BelongsToMany
    {
		return $this->belongsToMany(User::class, 'friendships', 'second_user', 'first_user')
					->withPivot('status', 'acted_user')
					->wherePivot('status', 'blocked')
					->wherePivot('acted_user', 'second_user');
	}

	/**
	 * accessor allowing to call $user->blocked_friends
	 * */
	public function getBlockedFriendsAttribute()
	{
		if ( ! array_key_exists('blocked_friends', $this->relations)) $this->loadBlockedFriends();
			return $this->getRelation('blocked_friends');
	}

	protected function loadBlockedFriends(): void
    {
		if ( ! array_key_exists('blocked_friends', $this->relations))
		{
			$friends = $this->mergeBlockedFriends();
			$this->setRelation('blocked_friends', $friends);
		}
	}

	protected function mergeBlockedFriends()
	{
		if ($temp = $this->friendsOfThisUserBlocked())
			return $temp->merge($this->thisUserFriendOfBlocked());
		else
			return $this->thisUserFriendOfBlocked();
	}

}
