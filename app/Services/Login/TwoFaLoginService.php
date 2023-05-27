<?php

namespace App\Services\Login;

use App\Exceptions\InvalidCredentialsException;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TwoFaLoginService extends BaseLoginService
{

    /**
     * @param array $inputs
     * @return array
     * @throws AuthorizationException
     * @throws InvalidCredentialsException
     */
    public function authenticateAndLogin(array $inputs): array
    {
        $secret = Arr::get($inputs, 'twoFactorSecret');
        $token = Arr::get($inputs, 'twoFactorIdentifier',);
        /** @var Google2faService $google2faService */
        $google2faService = app(Google2faService::class);
        $userId = $google2faService->identifyGetUserId($token);

        if (!$userId) {
            throw new BadRequestHttpException(__('Invalid two factor identifier.'));
        }

        /** @var User $user */
        $user = User::select(['id', 'username', 'password', 'email'])
            ->where('id', $userId)
            ->with('userSetting')
            ->first();

        if (!$user) {
            throw new BadRequestHttpException(__('Invalid two factor identifier.'));
        }

        $securitySettings = $user->userSetting;
        if (!$google2faService->verify2faCode($securitySettings->google2fa_secret, $secret)) {
            $this->updateInvalidLoginAttempt($user);
        }

        $data = $this->loginAuthenticatedUser($user);

        return [
            'data' => $data,
            'code' => Response::HTTP_OK,
            'message' => $message ?? null,
            'type' => $type ?? null
        ];
    }
}
