<?php

namespace App\Constants;

class AppConstants
{
    public const QUEUE_REDIS_READ_WRITE_TEST = 'read-write-test';
    public const CHANNEL_REDIS_READ_WRITE_TEST = 'redis-read-write-test';
    public const QUEUE_LONG_RUNNING = 'long-running-queue';
    public const PUSH_NOTIFICATIONS = 'push-notifications';
    public const LOGIN = 'login-events';
    public const SCOPE_WEBSITE_API = 'scope_website_api';
    public const SCOPE_ANOTHER_THING = 'scope_for_another_thing';
    public const DEFAULT_PASSPORT_TOKEN_NAME = 'passport_token';

    public const NOTIFICATION_KEY_LOGIN_429 = 'LOGIN_429';
    public const NOTIFICATION_KEY_LOGIN_403 = 'LOGIN_403';
    public const NOTIFICATION_KEY_LOGIN_401 = 'LOGIN_401';
    public const NOTIFICATION_KEY_LOGIN_423 = 'LOGIN_423';
    public const NOTIFICATION_KEY_FORGOT_PASSWORD_200 = 'FORGOT_PASSWORD_200';
    public const NOTIFICATION_KEY_TWO_FA_AUTHENTICATION_ENABLED = 'NOTIFICATION_KEY_TWO_FA_AUTHENTICATION_ENABLED';
    public const NOTIFICATION_KEY_TWO_FA_AUTHENTICATION_DISABLED = 'NOTIFICATION_KEY_TWO_FA_AUTHENTICATION_DISABLED';

}
