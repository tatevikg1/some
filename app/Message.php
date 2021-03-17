<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

  /**
   * Fields that are mass assignable
   *
   * @var array
   */
  protected $fillable = ['text','sender', 'receiver', 'read'];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  /**
   * loads the user object from the message object
  */
  public function fromContact()
  {
    return $this->hasOne(User::class, 'id', 'sender');
  }
}
