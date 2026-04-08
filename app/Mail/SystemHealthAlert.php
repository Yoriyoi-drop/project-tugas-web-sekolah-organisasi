<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SystemHealthAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $alerts;
    public $message;

    /**
     * Create a new message instance.
     */
    public function __construct(array $data)
    {
        $this->alerts = $data['alerts'] ?? [];
        $this->message = $data['message'] ?? '';
    }

    /**
     * Build the message.
     */
    public function build(): static
    {
        return $this->subject('System Health Alert')
                    ->view('emails.system-health-alert')
                    ->with([
                        'alerts' => $this->alerts,
                        'alertMessage' => $this->message,
                    ]);
    }
}
