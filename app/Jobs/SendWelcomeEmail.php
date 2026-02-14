<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\User;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewPostPublished;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        try {
            // Send welcome email
            Mail::to($this->user->email)->send(new WelcomeMail($this->user));

            // Send welcome notification
            $this->user->notify(new NewPostPublished());

            // Log successful delivery
            \Log::info("Welcome email sent successfully", [
                'user_id' => $this->user->id,
                'email' => $this->user->email,
                'timestamp' => now()
            ]);

            return true;
        } catch (\Exception $e) {
            // Log error and throw exception to retry job
            \Log::error("Failed to send welcome email", [
                'user_id' => $this->user->id,
                'email' => $this->user->email,
                'error' => $e->getMessage(),
                'timestamp' => now()
            ]);

            throw $e;
        }
    }
}
