<?php

use App\Jobs\RabbitMQ\SentTestRabbitMQJob;

return [
    'class_mapping' => [
        SentTestRabbitMQJob::JOB_KEY => SentTestRabbitMQJob::class,
    ],
    'sslOptions' => [
        'cafile' => '/path/to/ca.pem',
        'local_cert' => '/path/to/client.pem',
        'local_pk' => '/path/to/client-key.pem',
        'verify_peer' => true,
    ],
    'connections' => [
        'default' => [
            'host' => env('MQ_HOST', 'some-rabbitmq'),
            'port' => env('MQ_PORT', 5672),
            'vhost' => env('MQ_VHOST', 'some'),
            'login' => env('MQ_USER', 'guest'),
            'password' => env('MQ_PASS', 'guest'),
        ],
    ],
];
