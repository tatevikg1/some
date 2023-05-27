<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class InvalidSocialLoginException extends Exception implements HttpExceptionInterface
{
    public function __construct($message = "Invalid credential provided for social login.", $code = 401, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getStatusCode(): int
    {
        return 401;
    }

    public function getHeaders(): array
    {
        return [];
    }
}
