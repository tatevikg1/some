<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class UserLoginAttempt
 *
 * @property int $id
 * @property int $attempt_count
 * @property bool $status
 * @property int $blocked_for_seconds
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property User $user
 *
 * @package App\Models
 * @method create(array $array)
 */
class LoginAttempt extends BaseModel
{
    public const STATUS_UNLOCKED = 1;
    public const STATUS_LOCKED = 0;
    public $incrementing = false;
    protected $table = 'login_attempts';
    protected $casts = [
        'id' => 'int',
        'attempt_count' => 'int',
        'status' => 'bool',
        'blocked_for_seconds' => 'int'
    ];

    protected $fillable = [
        'attempt_count',
        'status',
        'blocked_for_seconds'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id');
    }
}
