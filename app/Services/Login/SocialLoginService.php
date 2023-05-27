<?php

namespace App\Services\Login;

use App\Exceptions\InvalidSocialLoginException;
use App\Models\User;
use Exception;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response;

class SocialLoginService
{
    /**
     * @throws InvalidSocialLoginException
     */
    public function login(array $input): array
    {
        try {
            $social = Socialite::driver($input['network'])->userFromToken($input['token']);
        } catch (Exception $exception) {
            throw new InvalidSocialLoginException($exception->getMessage());
        }

        $user = User::where('email', $social->user()->email)->first();

        if (is_null($user)) {
            $user = User::create(
                [
                    'phone' => null,
                    'email' => $social->user()->email,
                    'password' => $input['token'],
                ]
            );
        }
        $data = ['access_token' => $user->createToken('Auth social')->accessToken];

        return [
            'data' => $data,
            'code' => Response::HTTP_OK,
            'message' => $message ?? null,
            'type' => $type ?? null,
        ];
    }
}
