<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @property integer $id
 * @property integer $first_user
 * @property integer $second_user
 * @property integer $acted_user
 * @property bool $status
 *
*/
class Friendship extends Model
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

    public function delete()
    {
        DB::table('notifications')->where('data', "{\"id\":$this->id}")->delete();
        parent::delete();
    }
}
