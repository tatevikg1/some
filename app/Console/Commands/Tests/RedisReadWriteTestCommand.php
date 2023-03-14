<?php

namespace App\Console\Commands\Tests;

use App\Constants\AppConstants;
use App\Jobs\Tests\RedisReadWriteTestJob;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Queue;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class RedisReadWriteTestCommand extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:redis-rw {--sleep=10} {--iterations=100} {--keysCount=10}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test command to SET/UNSET keys into the Redis';

    protected array $keys = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        if ($running = Queue::size(AppConstants::QUEUE_REDIS_READ_WRITE_TEST)) {
            $this->info("{$running} Queues already running.. skipping");
            return SymfonyCommand::SUCCESS;
        }
        $cronCount = env('SUPERVISOR_REDIS_RW_TEST_MAX_PROCESS', 100);
        $keysCount = $this->option('keysCount');
        $iterations = $this->option('iterations');
        $sleep = $this->option('sleep');
        for ($i = 0; $i < $cronCount; $i++) {
            $name = 'cron-' . $i;
            $this->dispatch(new RedisReadWriteTestJob($name, $keysCount, $iterations, $sleep));
            $this->info(now()->toDateTimeString() . " - STARTED {$name}");
        }

        return SymfonyCommand::SUCCESS;
    }
}
