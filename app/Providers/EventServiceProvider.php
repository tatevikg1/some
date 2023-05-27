<?php

namespace App\Providers;

use App\Events\NewFriendRequestEvent;
use App\Events\UserLoggedInViaMasterPasswordEvent;
use App\Listeners\NewFriendRequestListener;
use App\Listeners\UserLoggedInViaMasterPasswordListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        NewFriendRequestEvent::class => [
            NewFriendRequestListener::class,
        ],
        UserLoggedInViaMasterPasswordEvent::class => [
            UserLoggedInViaMasterPasswordListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot(): void
    {
        parent::boot();
    }
}
