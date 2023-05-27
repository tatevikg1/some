<?php

namespace App\Constants;

class ErrorConstants
{
    const TYPE_INTERNAL_SERVER_ERROR = 'INTERNAL_SERVER_ERROR';
    const TYPE_VALIDATION_ERROR = 'VALIDATION';
    const TYPE_AUTHORIZATION_ERROR = 'AUTHORIZATION';

    const TYPE_METHOD_NOT_ALLOWED_ERROR = 'METHOD_NOT_ALLOWED';
    const TYPE_RESOURCE_NOT_FOUND_ERROR = 'RESOURCE_NOT_FOUND';
    const TYPE_SERVICE_NOT_IMPLEMENTED_ERROR = 'SERVICE_NOT_IMPLEMENTED';
    const TYPE_INVALID_CREDENTIALS_ERROR = 'INVALID_CREDENTIALS';
    const TYPE_BAD_REQUEST_ERROR = 'BAD_REQUEST';
    const TYPE_TOO_MANY_REQUEST_ERROR = 'TOO_MANY_REQUEST';
    const TYPE_GOOGLE2FA_REQUIRED_ERROR = 'TYPE_GOOGLE2FA_REQUIRED_ERROR';
    const TYPE_GOOGLE2FA_INVALID_CODE_ERROR = 'TYPE_GOOGLE2FA_INVALID_CODE_ERROR';
    const TYPE_GOOGLE2FA_ALREADY_ENABLED_ERROR = 'TYPE_GOOGLE2FA_ALREADY_ENABLED_ERROR';
    const TYPE_BAD_GATEWAY_ERROR = 'TYPE_BAD_GATEWAY_ERROR';
}
