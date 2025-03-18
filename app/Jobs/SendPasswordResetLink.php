<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendPasswordResetLink implements ShouldQueue
{
    use Queueable;

    protected $credentials;

    /**
     * Create a new job instance.
     */
    public function __construct(array $credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        app('auth.password.broker')->sendResetLink($this->credentials);
    }
}
