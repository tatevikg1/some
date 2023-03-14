<?php

namespace App\Jobs;

use App\Models\Message;
use App\Traits\JobDebugger;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SetMessagesAsRead implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels, InteractsWithQueue, JobDebugger;

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
