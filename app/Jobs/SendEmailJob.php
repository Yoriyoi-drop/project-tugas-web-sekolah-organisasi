<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $retryAfter = 60;

    /**
     * The maximum number of unhandled exceptions to allow before failing.
     */
    public int $maxExceptions = 3;

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 120;

    protected $emailData;
    protected $emailClass;
    protected $recipient;

    /**
     * Create a new job instance.
     */
    public function __construct($recipient, $emailClass, $emailData)
    {
        $this->recipient = $recipient;
        $this->emailClass = $emailClass;
        $this->emailData = $emailData;

        // Set queue based on email type
        $this->onQueue('emails');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info('Sending email job started', [
                'recipient' => $this->recipient,
                'email_class' => $this->emailClass,
                'job_id' => $this->job->getJobId(),
            ]);

            $email = new $this->emailClass($this->emailData);
            Mail::to($this->recipient)->send($email);

            Log::info('Email sent successfully', [
                'recipient' => $this->recipient,
                'email_class' => $this->emailClass,
                'job_id' => $this->job->getJobId(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send email', [
                'recipient' => $this->recipient,
                'email_class' => $this->emailClass,
                'error' => $e->getMessage(),
                'job_id' => $this->job->getJobId(),
                'attempt' => $this->attempts(),
            ]);

            // Re-throw the exception to trigger retry mechanism
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Email job failed permanently', [
            'recipient' => $this->recipient,
            'email_class' => $this->emailClass,
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts(),
            'job_id' => $this->job->getJobId(),
        ]);

        // Send notification to admin about failed email
        $this->notifyAdminAboutFailure($exception);
    }

    /**
     * Notify admin about email failure.
     */
    protected function notifyAdminAboutFailure(\Throwable $exception): void
    {
        try {
            $adminEmail = config('mail.admin_email', 'admin@example.com');
            
            Mail::raw(
                "Email sending failed permanently:\n\n" .
                "Recipient: {$this->recipient}\n" .
                "Email Class: {$this->emailClass}\n" .
                "Error: {$exception->getMessage()}\n" .
                "Attempts: {$this->attempts()}\n" .
                "Job ID: {$this->job->getJobId()}",
                function ($message) use ($adminEmail) {
                    $message->to($adminEmail)
                           ->subject('Email Sending Failed - ' . config('app.name'));
                }
            );
        } catch (\Exception $e) {
            Log::error('Failed to notify admin about email failure', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
