<?php

declare(strict_types=1);

namespace App\Jobs\RabbitMQ;

class SentTestRabbitMQJob extends RabbitMQJob
{
    public const JOB_KEY = 'test-queue-job';

    public function work(array $data): void
    {
        dump($data['message']);
    }
}
