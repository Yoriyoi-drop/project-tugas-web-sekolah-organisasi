<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendEmailNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $recipient;
    protected $subject;
    protected $template;
    protected $data;

    public function __construct($recipient, $subject, $template, $data = [])
    {
        $this->recipient = $recipient;
        $this->subject = $subject;
        $this->template = $template;
        $this->data = $data;
    }

    public function handle()
    {
        try {
            // Send email using the provided template class
            $mailable = new $this->template($this->data);
            Mail::to($this->recipient)
                ->send($mailable);

            // Log successful email delivery
            Log::info("Email notification sent successfully", [
                'recipient' => $this->recipient,
                'subject' => $this->subject,
                'timestamp' => now()
            ]);

            return true;
        } catch (\Exception $e) {
            // Log error and throw exception to retry job
            Log::error("Failed to send email notification", [
                'recipient' => $this->recipient,
                'subject' => $this->subject,
                'error' => $e->getMessage(),
                'timestamp' => now()
            ]);

            throw $e;
        }
    }
}
