<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Friendship extends Model
{
    protected $fillable = ['first_user', 'second_user', 'acted_user', 'status',];

    public static function recordReletedTo($user)
    {
        $record =  DB::table('friendships')
            ->where([['first_user', auth()->user()->id], ['second_user', $user->id]])
            ->orWhere([['first_user', $user->id], ['second_user', auth()->user()->id ]])
            ->first('id');
        if($record){
            return Friendship::where('id', $record->id)->first();
        }
        return null;
    }

    // public function creator()
    // {
    //     return $this->belongsTo(User::class, 'first_user');
    // }


}
