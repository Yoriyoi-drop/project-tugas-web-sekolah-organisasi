<?php
namespace App\Listeners;

use App\Events\UserRegistered;
use App\Mail\WelcomeMail;
use App\Notifications\NewPostPublished;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class SendWelcomeNotification
{
    public function handle($event)
    {
        // Check if event has a user property
        if (property_exists($event, 'user')) {
            $user = $event->user;
        } elseif (is_callable([$event, 'getUser'])) {
            $user = $event->getUser();
        } else {
            // If event is a User model directly
            $user = $event;
        }

        // Send welcome email
        Mail::to($user->email)->send(new WelcomeMail($user));

        // Send notification
        $user->notify(new NewPostPublished());

        // Log the notification
        \Log::info("Welcome notification sent to user: {$user->email}", [
            'user_id' => $user->id,
            'timestamp' => now()
        ]);
    }
}
