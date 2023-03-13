<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MessagesExport extends Model
{
    protected $fillable = [
        'user_id',
        'filename'
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
