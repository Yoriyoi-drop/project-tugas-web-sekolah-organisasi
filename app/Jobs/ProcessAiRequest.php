<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessAiRequest implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $payload;

    /**
     * Create a new job instance.
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Placeholder: perform asynchronous work such as calling an external AI service,
        // storing results, or dispatching follow-up jobs. Keep implementation minimal
        // to avoid side effects during modernization.

        // Example pseudocode:
        // $result = app(\App\Services\AiService::class)->ask($this->payload['question']);
        // // store or emit event
    }
}
