<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserLoggedInViaMasterPasswordEvent
{
    use Dispatchable, SerializesModels;

    private array $data;

    public function __construct(User $user, string $ip)
    {
        $this->data['user_id'] = $user->id;
        $this->data['user_ip_address'] = $ip;
        $this->data['login_time'] = now()->toDateTimeString();
    }

    public function getUserId(): int
    {
        return $this->data['user_id'];
    }

    public function getUserIp(): string
    {
        return $this->data['user_ip_address'];
    }

    public function getTime(): string
    {
        return $this->data['login_time'];
    }
}
