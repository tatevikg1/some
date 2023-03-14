<?php

namespace App\Helpers;

class HorizonHelper
{
    /**
     * returns the list of supervisors for local environment.
     * @return array[]
     */
    public static function getLocalSupervisors(): array
    {
        $supervisors = [
            'defaults' => [
                'connection' => 'redis',
                'queue' => [
                    config('constants.QUEUE.QUEUE_DEFAULT_WEBSOCKET'),
                    'default',
                ],
                'balance' => 'auto',
                'minProcesses' => config('constants.SUPERVISOR_DEFAULT_MIN_PROCESS', 1),
                'maxProcesses' => config('constants.SUPERVISOR_DEFAULT_MAX_PROCESS', 2),
                'tries' => 3,
                'memory' => 100,
                'timeout' => 60,
            ],
            'supervisor-example' => [
                'connection' => 'redis',
                'queue' => [
                    'set-message-as-read',
                ],
                'balance' => 'auto',
                'minProcesses' => config('constants.SUPERVISOR_example_MIN_PROCESS', 1),
                'maxProcesses' => config('constants.SUPERVISOR_example_MAX_PROCESS', 1),
                'tries' => 3,
                'memory' => 250
            ],
        ];

        if (config('constants.SUPERVISOR_LONG_RUNNING_ENABLED')) {
            $supervisors['supervisor-long-running'] = [
                'connection' => 'redis-long-running',
                'queue' => [
                    config('constants.QUEUE_DEFAULT_LONG_RUNNING'),
                ],
                'balance' => 'auto',
                'minProcesses' => config('constants.SUPERVISOR_LONG_RUNNING_MIN_PROCESS', 1),
                'maxProcesses' => config('constants.SUPERVISOR_LONG_RUNNING_MAX_PROCESS', 1),
                'tries' => 3,
                'memory' => 250,
                'timeout' => (2 * 60 * 60)  // 2 hours
            ];
        }
        return $supervisors;
    }

    /**
     * returns the list of supervisors for dev environment.
     * @return array[]
     */
    public static function getDevSupervisors(): array
    {
        return self::getLocalSupervisors();
    }

    /**
     * returns the list of supervisors for production environment.
     * @return array[]
     */
    public static function getProductionSupervisors(): array
    {
        return self::getDevSupervisors();
    }
}
