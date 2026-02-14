<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;

class SendNotificationCommand extends Command
{
    protected $signature = 'notify:send {--type=} {--user=} {--message=}';
    protected $description = 'Send system notifications';

    public function handle()
    {
        $type = $this->option('type') ?: 'system';
        $userId = $this->option('user');
        $message = $this->option('message') ?: 'System notification';

        $this->info("Sending {$type} notification...");

        try {
            $notification = new GeneralNotification($message, $type);

            if ($userId) {
                // Send to specific user
                $user = User::find($userId);
                if (!$user) {
                    $this->error("User with ID {$userId} not found.");
                    return 1;
                }
                
                $user->notify($notification);
                $this->info("Notification sent to user: {$user->name}");
            } else {
                // Send to all users
                $users = User::where('is_active', true)->get();
                
                if ($users->isEmpty()) {
                    $this->warn("No active users found.");
                    return 0;
                }

                Notification::send($users, $notification);
                $this->info("Notification sent to {$users->count()} users.");
            }

            $this->info("Notification sent successfully!");
        } catch (\Exception $e) {
            $this->error("Error sending notification: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
