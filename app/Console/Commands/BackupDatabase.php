<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'backup:database {--compress : Compress the backup file}';

    /**
     * The console command description.
     */
    protected $description = 'Create a backup of the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting database backup...');

        try {
            $filename = 'backup_' . Carbon::now()->format('Y_m_d_His') . '.sql';
            $path = storage_path('app/backups/' . $filename);

            // Ensure backup directory exists
            $backupDir = storage_path('app/backups');
            if (!File::exists($backupDir)) {
                File::makeDirectory($backupDir, 0755, true);
            }

            // Get database configuration
            $database = config('database.connections.' . config('database.default'));
            
            // Create backup command based on database type
            $command = $this->getBackupCommand($database, $path);
            
            // Execute backup
            $this->info('Creating backup file...');
            $result = $this->executeBackupCommand($command);

            if ($result) {
                $this->info('Database backup created successfully: ' . $filename);
                
                // Compress if requested
                if ($this->option('compress')) {
                    $this->compressBackup($path);
                    $filename .= '.gz';
                    $this->info('Backup compressed: ' . $filename);
                }

                // Clean old backups (keep last 7 days)
                $this->cleanOldBackups();

                // Log backup completion
                \Log::info('Database backup completed', [
                    'filename' => $filename,
                    'size' => filesize($path . ($this->option('compress') ? '.gz' : '')),
                    'compressed' => $this->option('compress')
                ]);

                return 0;
            } else {
                $this->error('Database backup failed');
                return 1;
            }
        } catch (\Exception $e) {
            $this->error('Backup failed: ' . $e->getMessage());
            \Log::error('Database backup failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }

    /**
     * Get backup command based on database type
     */
    protected function getBackupCommand($database, $path)
    {
        switch ($database['driver']) {
            case 'mysql':
                return sprintf(
                    'mysqldump -h%s -u%s -p%s %s > %s',
                    $database['host'],
                    $database['username'],
                    $database['password'],
                    $database['database'],
                    $path
                );
            
            case 'sqlite':
                return sprintf('sqlite3 %s .dump > %s', $database['database'], $path);
            
            case 'pgsql':
                return sprintf(
                    'PGPASSWORD=%s pg_dump -h%s -U%s -d%s > %s',
                    $database['password'],
                    $database['host'],
                    $database['username'],
                    $database['database'],
                    $path
                );
            
            default:
                throw new \Exception('Unsupported database driver: ' . $database['driver']);
        }
    }

    /**
     * Execute backup command
     */
    protected function executeBackupCommand($command)
    {
        // Hide password in command for logging
        $safeCommand = preg_replace('/-p\S+/', '-p****', $command);
        $this->info('Executing: ' . $safeCommand);

        // Use proc_open for better security and control
        $descriptorspec = [
            0 => ["pipe", "r"],  // stdin
            1 => ["pipe", "w"],  // stdout
            2 => ["pipe", "w"]   // stderr
        ];

        $process = proc_open($command, $descriptorspec, $pipes);

        if (is_resource($process)) {
            // Close pipes
            fclose($pipes[0]);
            $output = stream_get_contents($pipes[1]);
            $error_output = stream_get_contents($pipes[2]);
            fclose($pipes[1]);
            fclose($pipes[2]);

            $returnCode = proc_close($process);

            if ($returnCode !== 0) {
                $this->error('Backup command failed with error: ' . $error_output);
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Compress backup file
     */
    protected function compressBackup($path)
    {
        $this->info('Compressing backup file...');
        
        // Validate the path to prevent command injection
        $path = escapeshellarg($path);
        
        // Use proc_open for better security and control
        $descriptorspec = [
            0 => ["pipe", "r"],
            1 => ["pipe", "w"],
            2 => ["pipe", "w"]
        ];

        $process = proc_open("gzip $path", $descriptorspec, $pipes);

        if (is_resource($process)) {
            // Close pipes
            fclose($pipes[0]);
            $output = stream_get_contents($pipes[1]);
            $error_output = stream_get_contents($pipes[2]);
            fclose($pipes[1]);
            fclose($pipes[2]);

            $returnCode = proc_close($process);

            if ($returnCode !== 0) {
                $this->error('Compression failed with error: ' . $error_output);
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Clean old backup files (keep last 7 days)
     */
    protected function cleanOldBackups()
    {
        $backupDir = storage_path('app/backups');
        $files = glob($backupDir . 'backup_*.sql*');
        
        $cutoffDate = Carbon::now()->subDays(7);
        $deletedCount = 0;

        foreach ($files as $file) {
            if (filemtime($file) < $cutoffDate->timestamp) {
                unlink($file);
                $deletedCount++;
            }
        }

        if ($deletedCount > 0) {
            $this->info("Deleted {$deletedCount} old backup files");
        }
    }
}
