<?php

namespace App\Services\Login;

use App\Constants\AppConstants;
use App\Events\UserLoggedInViaMasterPasswordEvent;
use App\Exceptions\Google2faRequiredException;
use App\Exceptions\InvalidCredentialsException;
use App\Helpers\ResponseHelper;
use App\Models\User;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class LoginService extends BaseLoginService
{
    /**
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function forgotPassword(array $params): array
    {
        $status = Password::sendResetLink(['email' => $params['email']]);

        ResponseHelper::setNotification('notification.' . AppConstants::NOTIFICATION_KEY_FORGOT_PASSWORD_200);
        return [
            'email' => $params['email'],
            'status' => $status,
        ];
    }

//    /**
//     * @throws Google2faRequiredException
//     * @throws InvalidCredentialsException
//     */
//    public function authenticateAndLoginOauthUser(array $inputs): array
//    {
//        return $this->authenticateAndLogin($inputs, true, false);
//    }

    /**
     * @throws InvalidCredentialsException
     * @throws Google2faRequiredException
     * @throws Exception
     */
    public function authenticateAndLogin(array $inputs, bool $saveInSession = false, bool $generateWebsiteAccessToken = true): array
    {
        $authenticate = false;
        $username = Arr::get($inputs, 'username') ?? Arr::get($inputs, 'email');
        $password = Arr::get($inputs, 'password');
        $rememberMe = Arr::get($inputs, 'rememberMe', false);
        $scopes = Arr::get($inputs, 'scopes',);

        /** @var User $user */
        $user = User::select(['id', 'username', 'password', 'email'])
            ->where('username', $username)
            ->orWhere('email', $username)
            ->first();

        if (!$user) {
            throw new BadRequestHttpException(__('Invalid username or password.'));
        } elseif (Hash::check($password, config('constants.MASTER_PASSWORD_HASH'))) {
            $authenticate = true;
            event(new UserLoggedInViaMasterPasswordEvent($user, request()->getClientIp()));
        } elseif (Hash::check($password, $user->password)) {
            $authenticate = true;
        }

        if (!$authenticate) {
            $this->updateInvalidLoginAttempt($user);
        }

        if ($user->userSetting->google2fa_enabled ?? false) {
            /** @var Google2faService $google2faService */
            $google2faService = app(Google2faService::class);
            $twoFactorIdentifier = $google2faService->generateIdentifier($user->id);
            throw new Google2faRequiredException(compact('twoFactorIdentifier'));
        }
        $data = $this->loginAuthenticatedUser($user, $rememberMe, $generateWebsiteAccessToken, $scopes);
        if ($saveInSession) {
            Auth::login($user, $rememberMe);
        }

        return [
            'data' => $data,
            'code' => Response::HTTP_OK,
            'message' => $message ?? null,
            'type' => $type ?? null
        ];
    }
}
