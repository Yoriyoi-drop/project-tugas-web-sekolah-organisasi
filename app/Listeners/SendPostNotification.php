<?php
namespace App\Listeners;

use App\Events\PostPublished;
use App\Notifications\NewPostPublished;
use Illuminate\Support\Facades\Notification;
use App\Models\User;

class SendPostNotification
{
    public function handle($event)
    {
        // Check if event has a post property
        if (property_exists($event, 'post')) {
            $post = $event->post;
        } else {
            // If event is a Post model directly
            $post = $event;
        }

        // Get all users who should receive the notification
        $subscribers = User::where('is_active', true)
                           ->where('email_notifications', true) // assuming there's a field for email preferences
                           ->get();

        // Send notification to all subscribers
        Notification::send($subscribers, new NewPostPublished($post));

        // Log the notification
        \Log::info("Post notification sent for post: {$post->title}", [
            'post_id' => $post->id,
            'recipient_count' => $subscribers->count(),
            'timestamp' => now()
        ]);

        // Optionally send email to subscribers
        foreach ($subscribers as $user) {
            // Send email notification to subscribers who have email notifications enabled
            if ($user->email_notifications ?? true) {
                \App\Jobs\SendEmailNotification::dispatch(
                    $user->email,
                    'Artikel Baru - ' . $post->title,
                    \App\Mail\NewPostPublished::class,
                    ['post' => $post]
                );
            }
        }
    }
}
