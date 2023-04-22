<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

/**
 * @property integer $id
 * @property integer $first_user
 * @property integer $second_user
 * @property integer $acted_user
 * @property User $creator
 * @property bool $status
 *
*/
class Friendship extends BaseModel
{
    protected $fillable = [
        'first_user',
        'second_user',
        'acted_user',
        'status',
    ];

    /**
     * returns all friendship records related to user (friend request, friend, blocked)
    */
    public static function recordRelatedTo($user)
    {
        /** @var Friendship $record */
        $record =  DB::table('friendships')
            ->where([
                ['first_user', auth()->id()],
                ['second_user', $user->id]
            ])
            ->orWhere([
                ['first_user', $user->id],
                ['second_user', auth()->id()]
            ])
            ->first();
        if ($record) {
            return Friendship::where('id', $record->id)->first();
        }
        return null;
    }

    public function delete(): void
    {
        DB::table('notifications')->where('data', json_encode(['id' => $this->id]))->delete();
        parent::delete();
    }

    public static function creator($userId): User
    {
        return User::find($userId);
    }
}
