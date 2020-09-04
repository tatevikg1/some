<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use App\User;

class Message extends Model
{

    /**
     * Fields that are mass assignable
     *
     * @var array
     */
    protected $fillable = ['text','from', 'to', 'read'];

    public function user()
    {
      return $this->belongsTo(User::class);
    }

    public function fromContact()
    {
        // it is for loading the user object from the message object
        return $this->hasOne(User::class, 'id', 'from');
    }
}
