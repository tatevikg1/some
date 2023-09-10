<?php

declare(strict_types=1);

namespace App\Jobs\RabbitMQ;

abstract class RabbitMQJob implements RabbitMQJobInterface
{
    public function __invoke($data): void
    {
        $this->work(json_decode($data->getBody(), true));
    }
}
