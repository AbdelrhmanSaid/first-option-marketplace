<?php

namespace App\Jobs;

use App\Models\Publisher;
use App\Models\User;
use App\Notifications\NewMemberNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNewMemberNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected User $user,
        protected Publisher $publisher
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->user->notify(new NewMemberNotification($this->publisher));
    }
}
