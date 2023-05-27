<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\Google2faAlreadyEnabledException;
use App\Exceptions\Google2faInvalidCodeException;
use App\Http\Requests\Login\Enable2FaRequest;
use App\Services\Login\Google2faService;
use Illuminate\Http\JsonResponse;
use PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException;
use PragmaRX\Google2FA\Exceptions\InvalidCharactersException;
use PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException;
use PragmaRX\Google2FAQRCode\Exceptions\MissingQrCodeServiceException;

class UserController extends BaseApiController
{
    /**
     * @throws IncompatibleWithGoogleAuthenticatorException
     * @throws Google2faAlreadyEnabledException
     * @throws MissingQrCodeServiceException
     * @throws InvalidCharactersException
     * @throws SecretKeyTooShortException
     */
    public function google2faGenerateQrCode(Google2faService $google2faService): JsonResponse
    {
        $qrCode = $google2faService->generateQrCode();
        return $this->standardResponse([
            'qr_code' => $qrCode,
        ]);
    }

    /**
     * @throws Google2faInvalidCodeException
     */
    public function google2faEnable(Enable2FaRequest $request, Google2faService $google2faService): JsonResponse
    {
        return $this->standardResponse($google2faService->enable($request['twoFactorCode']), __('Two factor authentication is enabled'));
    }

    public function google2faDisable(Google2faService $google2faService): JsonResponse
    {
        return $this->standardResponse($google2faService->disable(), __('Two factor authentication is disabled'));
    }
}
