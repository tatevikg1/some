<?php

namespace App\Services\Login;

use App\Constants\AppConstants;
use App\Exceptions\Google2faAlreadyEnabledException;
use App\Exceptions\Google2faInvalidCodeException;
use App\Helpers\EncryptionHelper;
use App\Helpers\ResponseHelper;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException;
use PragmaRX\Google2FA\Exceptions\InvalidCharactersException;
use PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException;
use PragmaRX\Google2FALaravel\Google2FA;
use PragmaRX\Google2FAQRCode\Exceptions\MissingQrCodeServiceException;
use Throwable;

class Google2faService
{
    private const IDENTIFIER_CACHE_PREFIX = 'authenticate_2fa';
    private const IDENTIFIED_CACHE_TIMEOUT = 900; //15 minutes

    /**
     * Generate secret key and get QR Code
     *
     * @return string
     * @throws MissingQrCodeServiceException
     * @throws IncompatibleWithGoogleAuthenticatorException
     * @throws InvalidCharactersException
     * @throws SecretKeyTooShortException|Google2faAlreadyEnabledException
     */
    public function generateQrCode(): string
    {
        /** @var User $user */
        $user = User::find(auth()->id());
        $securitySettings = $user->userSetting;
        if ($securitySettings->google2fa_enabled) {
            throw new Google2faAlreadyEnabledException();
        }
        /** @var Google2FA $google2fa */
        $google2fa = app('pragmarx.google2fa');
        $secretKey = $google2fa->generateSecretKey();
        $qrCodeSvg = $google2fa->getQRCodeInline(config('app.name'), $user->username, $secretKey);
        $securitySettings->google2fa_secret = $secretKey;
        $securitySettings->save();

        return 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);
    }

    /**
     * @param string $twoFactorCode
     * @return bool
     * @throws Google2faInvalidCodeException
     */
    public function enable(string $twoFactorCode): bool
    {
        /** @var User $user */
        $user = User::find(auth()->id());
        $securitySettings = $user->userSetting;

        if (!$this->verify2faCode($securitySettings->google2fa_secret, $twoFactorCode)) {
            throw new Google2faInvalidCodeException();
        }

        $securitySettings->google2fa_enabled = true;
        $securitySettings->save();
        ResponseHelper::setNotification('notification.' . AppConstants::NOTIFICATION_KEY_TWO_FA_AUTHENTICATION_ENABLED);
        return true;
    }

    /**
     * @throws Exception
     */
    public function generateIdentifier(int $userId): string
    {
        $identifierKey = Str::random(6);
        $identifierValue = Str::random(32);
        $cacheKey = self::IDENTIFIER_CACHE_PREFIX . '_' . $userId . '_' . $identifierKey;
        Cache::put($cacheKey, $identifierValue, self::IDENTIFIED_CACHE_TIMEOUT);
        return EncryptionHelper::mcEncrypt($userId . '/' . $identifierKey . '/' . $identifierValue);
    }

    private function parseIdentifier(string $identifier): array
    {
        try {
            $decryptedCode = EncryptionHelper::mcDecrypt($identifier);
        } catch (Throwable $exception) {
            $decryptedCode = '';
        }
        $array = explode('/', $decryptedCode);
        return [$array[0] ?? '', $array[1] ?? '', $array[2] ?? ''];
    }

    public function identifyGetUserId(string $identifier): ?int
    {
        [$userId, $identifierKey, $identifierValue] = self::parseIdentifier($identifier);
        if ($userId && $identifierKey && $identifierValue) {
            $cache = Cache::get(self::IDENTIFIER_CACHE_PREFIX . '_' . $userId . '_' . $identifierKey);
            if ($cache == $identifierValue) {
                return (int)$userId;
            }
        }
        return null;
    }

    /**
     * @param string $secret
     * @param string $code
     * @return bool|int
     */
    public function verify2faCode(string $secret, string $code): bool|int
    {
        try {
            /** @var Google2FA $google2fa */
            $google2fa = app('pragmarx.google2fa');
            return $google2fa->verifyKey($secret, $code);
        } catch (Throwable $exception) {
            report($exception);
            return false;
        }
    }

    /**
     * @return bool
     */
    public function disable(): bool
    {
        /** @var User $user */
        $user = User::find(auth()->id());
        ResponseHelper::setNotification('notification.' . AppConstants::NOTIFICATION_KEY_TWO_FA_AUTHENTICATION_DISABLED);

        return $user->userSetting()->update([
            'google2fa_enabled' => false,
            'google2fa_secret' => ''
        ]);
    }
}
