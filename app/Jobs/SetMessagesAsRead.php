<?php

namespace App\Jobs;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SetMessagesAsRead implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Collection $messages;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Collection $messages)
    {
        $this->messages = $messages;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $messageIds = $this->messages->pluck('id');
        Message::whereIn('id', $messageIds)->update(['read' => true]);
    }
}
