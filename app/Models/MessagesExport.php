<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;

class MessagesExport extends BaseModel
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
