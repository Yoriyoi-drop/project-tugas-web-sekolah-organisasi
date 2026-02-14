<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MonitorSystemHealth extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'monitor:health {--alert-threshold=90 : Alert threshold percentage}';

    /**
     * The console command description.
     */
    protected $description = 'Monitor system health and send alerts if needed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting system health monitoring...');

        $threshold = $this->option('alert-threshold');
        $alerts = [];

        // Check disk space
        $diskUsage = $this->checkDiskSpace();
        if ($diskUsage['percent'] > $threshold) {
            $alerts[] = "Disk usage is critical: {$diskUsage['percent']}%";
        }

        // Check memory usage
        $memoryUsage = $this->checkMemoryUsage();
        if ($memoryUsage['percent'] > $threshold) {
            $alerts[] = "Memory usage is critical: {$memoryUsage['percent']}%";
        }

        // Check database connection
        $dbStatus = $this->checkDatabase();
        if (!$dbStatus) {
            $alerts[] = "Database connection failed";
        }

        // Check cache system
        $cacheStatus = $this->checkCache();
        if (!$cacheStatus) {
            $alerts[] = "Cache system is not responding";
        }

        // Log results
        $this->logHealthCheck([
            'disk_usage' => $diskUsage,
            'memory_usage' => $memoryUsage,
            'database' => $dbStatus,
            'cache' => $cacheStatus,
            'alerts' => $alerts,
            'threshold' => $threshold
        ]);

        // Send alerts if any
        if (!empty($alerts)) {
            $this->sendAlerts($alerts);
            $this->error('System health issues detected:');
            foreach ($alerts as $alert) {
                $this->error('  - ' . $alert);
            }
            return 1;
        } else {
            $this->info('System health is normal');
            return 0;
        }
    }

    /**
     * Check disk space usage
     */
    protected function checkDiskSpace()
    {
        $freeSpace = disk_free_space(base_path());
        $totalSpace = disk_total_space(base_path());
        
        if ($freeSpace === false || $totalSpace === false) {
            return ['percent' => 0, 'free' => 0, 'total' => 0];
        }

        $usedSpace = $totalSpace - $freeSpace;
        $percent = round(($usedSpace / $totalSpace) * 100, 2);

        return [
            'percent' => $percent,
            'free' => $this->formatBytes($freeSpace),
            'total' => $this->formatBytes($totalSpace)
        ];
    }

    /**
     * Check memory usage
     */
    protected function checkMemoryUsage()
    {
        $memoryUsage = memory_get_usage(true);
        $memoryLimit = $this->parseMemoryLimit(ini_get('memory_limit'));
        $percent = round(($memoryUsage / $memoryLimit) * 100, 2);

        return [
            'percent' => $percent,
            'used' => $this->formatBytes($memoryUsage),
            'limit' => $this->formatBytes($memoryLimit)
        ];
    }

    /**
     * Check database connection
     */
    protected function checkDatabase()
    {
        try {
            \DB::connection()->getPdo();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check cache system
     */
    protected function checkCache()
    {
        try {
            $key = 'health_check_' . time();
            $value = 'test';
            
            \Cache::put($key, $value, 60);
            $retrieved = \Cache::get($key);
            \Cache::forget($key);

            return $retrieved === $value;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Log health check results
     */
    protected function logHealthCheck($data)
    {
        Log::info('System health check completed', [
            'timestamp' => Carbon::now()->toISOString(),
            'data' => $data
        ]);
    }

    /**
     * Send alerts (implement your preferred alert method)
     */
    protected function sendAlerts($alerts)
    {
        $message = "System Health Alert - " . Carbon::now()->format('Y-m-d H:i:s') . "\n\n";
        $message .= implode("\n", $alerts);

        // Log alert
        Log::error('System health alert', [
            'alerts' => $alerts,
            'message' => $message
        ]);

        // Send notification to admin users
        $adminUsers = \App\Models\User::where('is_admin', true)->get();
        
        foreach ($adminUsers as $admin) {
            // Send notification via database
            $admin->notify(new \App\Notifications\GeneralNotification(
                $message,
                'high'
            ));
        }

        // Send email to admin if configured
        $adminEmail = config('mail.from.address', 'admin@manu.com');
        if ($adminEmail) {
            \App\Jobs\SendEmailNotification::dispatch(
                $adminEmail,
                'System Health Alert',
                \App\Mail\SystemHealthAlert::class,
                ['alerts' => $alerts, 'message' => $message]
            );
        }

        // You can also implement Slack, Discord, or other notification methods here
        // Example: Send to Slack webhook if configured
        $slackWebhook = config('services.slack.webhook_url');
        if ($slackWebhook) {
            \Http::post($slackWebhook, [
                'text' => $message,
                'channel' => '#alerts',
                'username' => 'System Monitor Bot',
            ]);
        }
    }

    /**
     * Parse memory limit string to bytes
     */
    protected function parseMemoryLimit($limit)
    {
        $limit = trim($limit);
        $last = strtolower($limit[strlen($limit) - 1]);
        $value = (int) $limit;

        switch ($last) {
            case 'g':
                return $value * 1024 * 1024 * 1024;
            case 'm':
                return $value * 1024 * 1024;
            case 'k':
                return $value * 1024;
            default:
                return $value;
        }
    }

    /**
     * Format bytes to human readable format
     */
    protected function formatBytes($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
