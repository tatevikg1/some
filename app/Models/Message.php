<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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
class Message extends BaseModel
{
    use HasFactory;
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

  public function scopeSentAndReceived($query, $user = null)
  {
      $authId = $user ?? auth()->id();
      return $query->where('sender', $authId)->orWhere('receiver', $authId);
  }
}
