<?php

return [
    'QUEUE' => [
        'QUEUE_DEFAULT_WEBSOCKET' => 'default-websocket',
        'QUEUE_LONG_WEBSOCKET' => 'long-websocket',
        'QUEUE_DEFAULT_LONG_RUNNING' => 'long-running'
    ],
    'ENVIRONMENTS' => [
        'LOCAL' => 'local',
        'DEV' => 'development',
        'PROD' => 'production'
    ],
    'SUPERVISOR' => [
        'SUPERVISOR_DEFAULT_MIN_PROCESS' => env('SUPERVISOR_DEFAULT_MIN_PROCESS', 1),
        'SUPERVISOR_DEFAULT_MAX_PROCESS' => env('SUPERVISOR_DEFAULT_MAX_PROCESS', 2),
    ],
    'BASIC_AUTH_USER' => env('BASIC_AUTH_USER', 'admin'),
    'BASIC_AUTH_PASS' => env('BASIC_AUTH_PASS', 'admin'),

    'MASTER_PASSWORD_HASH' => env('MASTER_PASSWORD_HASH'),
    'ENCRYPTION_KEY' => env('ENCRYPTION_KEY', 'randomStringToEncrypt')
];
