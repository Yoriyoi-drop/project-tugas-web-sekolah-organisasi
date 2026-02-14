<?php
namespace App\Observers;

use App\Models\User;
use App\Models\SecurityLog;
use App\Services\SecurityService;

class UserObserver
{
    public function created($user)
    {
        // Log user creation
        SecurityService::logActivity(
            'user_created', 
            [
                'user_id' => $user->id,
                'email' => $user->email,
                'name' => $user->name
            ], 
            SecurityService::RISK_LOW,
            $user->id
        );

        // Create initial security log entry
        SecurityLog::create([
            'user_id' => $user->id,
            'action' => 'user_created',
            'ip_address' => request()?->ip() ?? 'system',
            'user_agent' => request()?->userAgent() ?? 'system',
            'data' => [
                'created_at' => now()->toISOString(),
                'initial_data' => [
                    'name' => $user->name,
                    'email' => $user->email
                ]
            ],
            'risk_level' => 'low'
        ]);

        // Log to application log
        \Log::info("New user registered: {$user->email}", [
            'user_id' => $user->id,
            'timestamp' => now()
        ]);
    }

    public function updated($user)
    {
        // Log profile updates
        SecurityService::logProfileUpdate($user->id, $user->getDirty());

        // Log to application log
        \Log::info("User profile updated: {$user->email}", [
            'user_id' => $user->id,
            'updated_fields' => array_keys($user->getDirty()),
            'timestamp' => now()
        ]);
    }

    public function deleted($user)
    {
        // Log user deletion
        SecurityService::logActivity(
            'user_deleted', 
            [
                'user_id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'deleted_at' => now()->toISOString()
            ], 
            SecurityService::RISK_MEDIUM
        );

        // Log to application log
        \Log::info("User deleted: {$user->email}", [
            'user_id' => $user->id,
            'timestamp' => now()
        ]);
    }

    public function restored($user)
    {
        // Log user restoration
        SecurityService::logActivity(
            'user_restored', 
            [
                'user_id' => $user->id,
                'email' => $user->email,
                'name' => $user->name
            ], 
            SecurityService::RISK_LOW
        );

        // Log to application log
        \Log::info("User restored: {$user->email}", [
            'user_id' => $user->id,
            'timestamp' => now()
        ]);
    }
}
