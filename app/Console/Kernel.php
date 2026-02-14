<?php
namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Schedule daily backup
        $schedule->command('backup:database --compress')->daily()->at('02:00');
        
        // Schedule daily cleanup of old logs
        $schedule->command('log:clean --days=30')->daily();
        
        // Schedule weekly report generation
        $schedule->command('report:generate weekly')->weeklyOn(1, '09:00');
        
        // Schedule monthly analytics report
        $schedule->command('report:generate monthly')->monthlyOn(1, '10:00');
        
        // Schedule cleanup of expired OTP codes
        $schedule->command('otp:cleanup')->hourly();
        
        // Schedule cleanup of old password reset tokens
        $schedule->command('auth:clear-resets')->daily();
        
        // Schedule monitoring system health check
        $schedule->command('monitor:health')->everyFiveMinutes();
        
        // Schedule cache garbage collection
        $schedule->command('cache:gc')->dailyAt('03:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
