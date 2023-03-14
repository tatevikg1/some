<?php

namespace App\Jobs\Tests;

use App\Constants\AppConstants;
use App\Traits\JobDebugger;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class RedisReadWriteTestJob implements ShouldQueue
{
    use JobDebugger;

    public string $queue = AppConstants::QUEUE_REDIS_READ_WRITE_TEST;

    public string $name;
    public int $keysCount;
    public int $iterations;
    public int $sleep;
    protected array $keys = [];

    /**
     * Create a new job instance.
     *
     * @param string $name
     * @param int $keysCount
     * @param int $iterations
     * @param int $sleep
     */
    public function __construct(string $name, int $keysCount, int $iterations, int $sleep)
    {
        $this->addDebugProperties();
        $this->name = $name;
        $this->keysCount = $keysCount;
        $this->iterations = $iterations;
        $this->sleep = $sleep;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $keysCount = $this->keysCount;
        for ($i = 0; $i < $keysCount; $i++) {
            array_push($this->keys, Str::random(5));
        }

        $iterations = $this->iterations;
        $sleep = $this->sleep;
        for ($i = 0; $i < $iterations; $i++) {
            Log::channel(AppConstants::CHANNEL_REDIS_READ_WRITE_TEST)->info("{$this->name} - Doing SET for iteration: [{$i}/{$iterations}]");
            foreach ($this->keys as $key) {
                Cache::put($key, Str::random(100));
            }

            Log::channel(AppConstants::CHANNEL_REDIS_READ_WRITE_TEST)->info("{$this->name} - Doing PULL (GET + DEL) for iteration: [{$i}/{$iterations}]");
            foreach ($this->keys as $key) {
                Cache::pull($key);
            }

            Log::channel(AppConstants::CHANNEL_REDIS_READ_WRITE_TEST)->info("{$this->name} - Sleeping for: {$sleep} seconds...");

            sleep($sleep);
            Log::channel(AppConstants::CHANNEL_REDIS_READ_WRITE_TEST)->info("---");
        }
    }

    /**
     * Handle a job failure.
     *
     * @param Throwable $exception
     * @return void
     */
    public function failed(Throwable $exception)
    {
        Log::channel(AppConstants::CHANNEL_REDIS_READ_WRITE_TEST)->error($exception->getMessage());
        Log::channel(AppConstants::CHANNEL_REDIS_READ_WRITE_TEST)->error($exception->getTraceAsString());
    }
}
