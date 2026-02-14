<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Contact;
use App\Services\ContactService;
use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ContactFormSubmitted;
use App\Models\User;

class ProcessContactForm implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $formData;

    public function __construct(array $formData)
    {
        $this->formData = $formData;
    }

    public function handle()
    {
        // Process the contact form data
        $contactService = new ContactService();
        $result = $contactService->submitContactForm($this->formData);

        if ($result['success']) {
            // Log successful processing
            \Log::info("Contact form processed successfully", [
                'contact_id' => $result['contact']->id,
                'email' => $result['contact']->email,
                'timestamp' => now()
            ]);

            return true;
        } else {
            // Log error
            \Log::error("Failed to process contact form", [
                'errors' => $result['errors'],
                'email' => $this->formData['email'] ?? 'unknown',
                'timestamp' => now()
            ]);

            // Throw exception to retry the job
            throw new \Exception("Failed to process contact form: " . json_encode($result['errors']));
        }
    }
}
