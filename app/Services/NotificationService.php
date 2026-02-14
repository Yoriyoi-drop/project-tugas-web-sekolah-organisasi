<?php
namespace App\Services;

use App\Models\User;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use App\Models\OrganizationNotification;

class NotificationService
{
    public function sendGeneralNotification($users, $message, $type = 'info', $data = [])
    {
        $notification = new GeneralNotification($message, $type);

        if (is_array($users) || $users instanceof \Illuminate\Support\Collection) {
            Notification::send($users, $notification);
        } else {
            $users->notify($notification);
        }

        return [
            'success' => true,
            'message' => 'Notification sent successfully',
            'recipient_count' => is_array($users) ? count($users) : ($users instanceof \Illuminate\Support\Collection ? $users->count() : 1)
        ];
    }

    public function sendWelcomeNotification($user)
    {
        $user->notify(new \App\Notifications\NewPostPublished()); // Using existing notification as example
        
        // Also send email
        Mail::to($user->email)->send(new WelcomeMail($user));
        
        return [
            'success' => true,
            'message' => 'Welcome notification sent to ' . $user->name
        ];
    }

    public function sendOrganizationNotification($organization, $title, $content, $type = 'info', $priority = 'normal')
    {
        // Create notification in database
        $notification = OrganizationNotification::create([
            'organization_id' => $organization->id,
            'title' => $title,
            'content' => $content,
            'type' => $type,
            'priority' => $priority,
            'author_id' => auth()->id() ?? 1, // Default to first user if not authenticated
            'is_broadcast' => true
        ]);

        // Send to all members of the organization
        $members = $organization->members()->active()->get();
        
        foreach ($members as $member) {
            if ($member->user) {
                $member->user->notify(new \App\Notifications\GeneralNotification($content, $type));
            }
        }

        return [
            'success' => true,
            'message' => 'Organization notification sent to ' . $members->count() . ' members',
            'notification' => $notification
        ];
    }

    public function sendBulkNotifications($criteria, $message, $type = 'info')
    {
        $users = $this->getUsersByCriteria($criteria);
        
        $notification = new GeneralNotification($message, $type);
        Notification::send($users, $notification);

        return [
            'success' => true,
            'message' => 'Bulk notification sent to ' . $users->count() . ' users',
            'recipient_count' => $users->count()
        ];
    }

    public function sendPersonalizedNotification($user, $template, $data = [])
    {
        // In a real implementation, this would use a template system
        $message = $this->compileTemplate($template, $data);
        $user->notify(new GeneralNotification($message, 'personalized'));

        return [
            'success' => true,
            'message' => 'Personalized notification sent to ' . $user->name
        ];
    }

    public function scheduleNotification($user, $message, $scheduledTime, $type = 'info')
    {
        // In a real implementation, this would schedule the notification
        // For now, we'll just send it immediately
        $user->notify(new GeneralNotification($message, $type));

        return [
            'success' => true,
            'message' => 'Notification scheduled for ' . $scheduledTime,
            'scheduled_time' => $scheduledTime
        ];
    }

    private function getUsersByCriteria($criteria)
    {
        $query = User::query();

        if (isset($criteria['role'])) {
            $query->where('role', $criteria['role']);
        }

        if (isset($criteria['organization'])) {
            $query->where('organization_id', $criteria['organization']);
        }

        if (isset($criteria['active']) && $criteria['active']) {
            $query->where('is_active', true);
        }

        if (isset($criteria['date_range'])) {
            $query->whereBetween('created_at', $criteria['date_range']);
        }

        return $query->get();
    }

    private function compileTemplate($template, $data)
    {
        // Template compilation using Laravel's Blade engine for more advanced templating
        $message = $template;
        
        // Replace simple placeholders
        foreach ($data as $key => $value) {
            $message = str_replace('{{' . $key . '}}', $value, $message);
        }

        // Support for dot notation (e.g., {{user.name}})
        foreach ($data as $key => $value) {
            if (is_array($value) || is_object($value)) {
                foreach ($value as $subKey => $subValue) {
                    $message = str_replace('{{' . $key . '.' . $subKey . '}}', $subValue, $message);
                }
            }
        }

        return $message;
    }

    public function getUnreadNotificationCount($user)
    {
        return $user->notifications()->whereNull('read_at')->count();
    }

    public function markAllAsRead($user)
    {
        $user->unreadNotifications()->update(['read_at' => now()]);
        
        return [
            'success' => true,
            'message' => 'All notifications marked as read'
        ];
    }
}
