<?php
namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\SecurityLog;
use App\Services\SecurityService;

class LogUserLogin
{
    public function handle($event)
    {
        // Check if event is a Login event
        if ($event instanceof Login) {
            $user = $event->user;
        } else {
            // If event is a User model directly
            $user = $event;
        }

        // Log the login activity
        SecurityService::logLogin($user->id);

        // Create security log entry
        SecurityLog::create([
            'user_id' => $user->id,
            'action' => 'login',
            'ip_address' => request()?->ip() ?? 'unknown',
            'user_agent' => request()?->userAgent() ?? 'unknown',
            'data' => [
                'login_time' => now()->toISOString(),
                'remember_me' => $event->remember ?? false
            ],
            'risk_level' => 'low'
        ]);

        // Update user's last login timestamp
        $user->update(['last_login_at' => now()]);

        // Log to application log
        \Log::info("User logged in: {$user->email}", [
            'user_id' => $user->id,
            'ip_address' => request()?->ip() ?? 'unknown',
            'timestamp' => now()
        ]);
    }
}
