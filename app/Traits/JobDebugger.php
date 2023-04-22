<?php

namespace App\Traits;

use Throwable;

trait JobDebugger
{
    public ?string $debugHostname = null;

    public function addDebugProperties(): void
    {
        try {
            $this->debugHostname = exec('hostname -f');
        } catch (Throwable $exception) {
            report($exception);
        }
    }
}
