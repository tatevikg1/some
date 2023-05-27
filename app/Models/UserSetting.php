<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class UserSetting
 *
 * @property int $id
 * @property bool $google2fa_enabled
 * @property string $google2fa_secret
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property User $user
 */
class UserSetting extends BaseModel
{
    use HasFactory;

    protected $hidden = [
        'google2fa_secret',
    ];

    protected $fillable = [
        'google2fa_enabled',
        'google2fa_secret',
    ];

    protected $casts = [
        'google2fa_enabled' => 'bool'
    ];

    protected $attributes = [
        'google2fa_enabled' => false,
        'google2fa_secret' => '',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id');
    }
}
