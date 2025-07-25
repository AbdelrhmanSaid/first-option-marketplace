<?php

namespace App\Jobs;

use App\Models\Admin;
use App\Notifications\NewAdminNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNewAdminNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected Admin $admin,
        protected string $password,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->admin->notify(new NewAdminNotification($this->password));
    }
}
