<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class Google2faRequiredException extends Exception implements HttpExceptionInterface
{
    private ?array $data;

    public function __construct(?array $data = null)
    {
        $this->data = $data;
        parent::__construct('Two factor authentication required', 403);
    }

    public function getData(): ?array
    {
        return $this->data;
    }


    public function getStatusCode(): int
    {
        return 403;
    }

    public function getHeaders(): array
    {
        return [];
    }
}
