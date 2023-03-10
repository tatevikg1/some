<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $text
 * @property integer $sender
 * @property integer $receiver
 * @property bool $read
 * @property User $fromContact
 */
class Message extends Model
{
  /**
   * Fields that are mass assignable
   *
   * @var array
   */
  protected $fillable = [
      'text',
      'sender',
      'receiver',
      'read'
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  /**
   * loads the user object from the message object
  */
  public function fromContact(): HasOne
  {
    return $this->hasOne(User::class, 'id', 'sender');
  }
}
