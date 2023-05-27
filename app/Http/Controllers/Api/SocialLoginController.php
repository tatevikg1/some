<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\InvalidSocialLoginException;
use App\Http\Requests\Login\SocialLoginRequest;
use App\Http\Requests\Login\SocialRedirectRequest;
use App\Models\SocialProfile;
use App\Services\Login\SocialLoginService;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends BaseApiController
{
    public function getSocialLoginCredentials(): JsonResponse
    {
        $result = [
            'google' => [
                'client_id' => config('constants.SOCIAL_LOGIN.GOOGLE_CLIENT_ID')
            ],
            'facebook' => [
                'app_id' => config('constants.SOCIAL_LOGIN.FACEBOOK_CLIENT_ID')
            ],
            'apple' => [
                'client_id' => config('constants.SOCIAL_LOGIN.APPLE_CLIENT_ID')
            ],
            'twitter' => [
                'app_key' => config('constants.SOCIAL_LOGIN.TWITTER_APP_ID')
            ],
        ];

        return $this->standardResponse($result);
    }
    /**
     * @throws InvalidSocialLoginException
     */
    public function authSocial(SocialLoginRequest $request, SocialLoginService $socialLoginService): JsonResponse
    {
        list($data, $code, $message, $type) = $socialLoginService->login($request->validated());
        return $this->standardResponse($data, $message, $code, $type);
    }

    public function redirect(SocialRedirectRequest $request): void
    {
        $type = $request->get('type');
        $key = array_search($type, SocialProfile::REDIRECT_MAP);
        switch ($type) {
            case SocialProfile::REDIRECT_MAP[SocialProfile::TYPE_APPLE]:
                Socialite::driver(SocialProfile::DRIVER_MAP[SocialProfile::TYPE_APPLE])->scopes(["name", "email"])->redirect();
                break;
            default:
        }

        Socialite::driver(SocialProfile::DRIVER_MAP[$key])->redirect();
    }
}
