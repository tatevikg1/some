<?php

namespace App\Services\Login;

use App\Constants\AppConstants;
use App\Exceptions\InvalidCredentialsException;
use App\Helpers\ResponseHelper;
use App\Models\LoginAttempt;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Laravel\Passport\Passport;

abstract class BaseLoginService
{
    protected const ALLOWED_ATTEMPTS = 3;
    protected const MAX_BLOCK_MINUTES = 60;

    /**
     * @throws InvalidCredentialsException
     */
    protected function updateInvalidLoginAttempt(User $user): void
    {
        if (!$user->loginAttempt()->exists()) {
            $this->resetLoginAttempts($user);
            ResponseHelper::setNotification('notification.' . AppConstants::NOTIFICATION_KEY_LOGIN_401);
            $message = 'Invalid login attempt';
            throw new InvalidCredentialsException($message);
        }
        if ($user->loginAttempt->updated_at->diffInMinutes(now()) < 10 || $this->userIsBlockedForMinutes($user)) {
            $attemptCount = $user->loginAttempt->attempt_count + 1;

            if ($attemptCount <= self::ALLOWED_ATTEMPTS) {
                $user->loginAttempt()->update(['attempt_count' => $attemptCount]);
                ResponseHelper::setNotification('notification.' . AppConstants::NOTIFICATION_KEY_LOGIN_401);
                $message = 'Invalid login attempt';
                throw new InvalidCredentialsException($message);
            }

            $blockedForMinutes = min(pow(1.5, ($attemptCount - self::ALLOWED_ATTEMPTS)), self::MAX_BLOCK_MINUTES);
            $updateData = ['attempt_count' => $attemptCount, 'blocked_for_seconds' => $blockedForMinutes * 60, 'status' => LoginAttempt::STATUS_LOCKED];
            $user->loginAttempt()->update($updateData);
            ResponseHelper::setNotification('notification.' . AppConstants::NOTIFICATION_KEY_LOGIN_429, ['minutes' => $blockedForMinutes]);
            $message = 'Too many login attempts';
            throw new ThrottleRequestsException($message);
        }
    }

    protected function resetLoginAttempts(User $user): void
    {
        $user->loginAttempt()->updateOrCreate(
            ['id' => $user->id],
            ['attempt_count' => 1, 'blocked_for_seconds' => null, 'status' => LoginAttempt::STATUS_UNLOCKED]
        );
    }

    protected function userIsBlockedForMinutes(User $user): int
    {
        if ($lastLoginAttempt = $user->loginAttempt) {
            if (Carbon::parse($lastLoginAttempt->updated_at)->addSeconds($lastLoginAttempt->blocked_for_seconds) > Carbon::now()) {
                $blockedForNextSeconds = (Carbon::parse($lastLoginAttempt->updated_at)->addSeconds($lastLoginAttempt->blocked_for_seconds))->diffInSeconds(Carbon::now());
                $blockedForMinutes = intval($blockedForNextSeconds / 60);
                if ($blockedForMinutes === 0) {
                    $blockedForMinutes = 1;
                }
                return $blockedForMinutes;
            }
            $user->loginAttempt()->update([
                'attempt_count' => 0,
                'blocked_for_seconds' => null,
                'status' => $lastLoginAttempt::STATUS_UNLOCKED,
            ]);
        }
        return 0;
    }

    public function loginAuthenticatedUser(User $user, $rememberMe = false, bool $generateWebsiteAccessToken = true, ?array $scopes = [AppConstants::SCOPE_WEBSITE_API]): User
    {
        if ($rememberMe) {
            Passport::personalAccessTokensExpireIn(Carbon::now()->addMonths(1));
        } else {
            Passport::personalAccessTokensExpireIn(Carbon::now()->addHours(24));
        }
        return self::getLoggedInUserData($user, $generateWebsiteAccessToken, $scopes);
    }

    /**
     * @param User $user
     * @param bool $generateWebsiteAccessToken
     * @param array $scopes
     * @return User
     */
    protected static function getLoggedInUserData(User $user, bool $generateWebsiteAccessToken = true, array $scopes = [AppConstants::SCOPE_WEBSITE_API]): User
    {
        /** @var User $user */
        $user = User::where('id', $user->id)
            ->with(['profile', 'userSetting', 'loginAttempt', 'socialConnects'])
            ->first();

        if ($generateWebsiteAccessToken) {
            $token = $user->createToken(AppConstants::DEFAULT_PASSPORT_TOKEN_NAME, $scopes)->accessToken;
        } else {
            $token = null;
        }
        $user->setAttribute('token', $token);

        return $user;
    }
}


