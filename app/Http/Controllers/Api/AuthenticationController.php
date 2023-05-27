<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\Google2faRequiredException;
use App\Exceptions\InvalidCredentialsException;
use App\Http\Requests\Login\ForgotPasswordRequest;
use App\Http\Requests\Login\LoginRequest;
use App\Http\Requests\Login\TwoFactorLoginRequest;
use App\Models\User;
use App\Services\Login\LoginService;
use App\Services\Login\TwoFaLoginService;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use ReflectionException;

class AuthenticationController extends BaseApiController
{
    /**
     * @throws InvalidCredentialsException
     * @throws Google2faRequiredException
     */
    public function login(LoginRequest $request, LoginService $loginService): JsonResponse
    {
        $response = $loginService->authenticateAndLogin($request->validated());

        return $this->standardResponse(
            Arr::pull($response, 'data'),
            Arr::pull($response, 'message'),
            Arr::pull($response, 'code'),
            Arr::pull($response, 'type')
        );
    }

    /**
     * @throws AuthorizationException
     * @throws InvalidCredentialsException
     */
    public function twoFactorLogin(TwoFactorLoginRequest $request, TwoFaLoginService $loginService): JsonResponse
    {
        $response = $loginService->authenticateAndLogin($request->validated());

        return $this->standardResponse(
            Arr::pull($response, 'data'),
            Arr::pull($response, 'message'),
            Arr::pull($response, 'code'),
            Arr::pull($response, 'type')
        );
    }

    /**
     * @throws ReflectionException|Exception
     */
    public function forgotPassword(ForgotPasswordRequest $request, LoginService $loginService): JsonResponse
    {
        $response = $loginService->forgotPassword($request->validated());
        return $this->standardResponse($response);
    }

    public function logout(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        $user->token()->revoke();
        return $this->standardResponse(null);
    }
}
