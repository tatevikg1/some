<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\RabbitMQService;
use Illuminate\Console\Command;

class MQConsumerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:consumer {job-key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    private RabbitMQService $rabbitMQService;

    public function __construct(RabbitMQService $mqService)
    {
        parent::__construct();
        $this->rabbitMQService = $mqService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->rabbitMQService->consume($this->argument('job-key'));
    }
}
