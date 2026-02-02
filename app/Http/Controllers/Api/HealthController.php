<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class HealthController extends Controller
{
    /**
     * Basic health check endpoint
     */
    public function health(): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'timestamp' => now()->toISOString(),
            'version' => config('app.version', '1.0.0'),
            'environment' => config('app.env'),
        ]);
    }

    /**
     * Detailed health check with system status
     */
    public function detailed(): JsonResponse
    {
        $checks = [
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'storage' => $this->checkStorage(),
            'memory' => $this->checkMemory(),
            'disk' => $this->checkDisk(),
        ];

        $overallStatus = collect($checks)->every(fn($check) => $check['status'] === 'ok') ? 'ok' : 'degraded';
        $statusCode = $overallStatus === 'ok' ? Response::HTTP_OK : Response::HTTP_SERVICE_UNAVAILABLE;

        return response()->json([
            'status' => $overallStatus,
            'timestamp' => now()->toISOString(),
            'version' => config('app.version', '1.0.0'),
            'environment' => config('app.env'),
            'checks' => $checks,
        ], $statusCode);
    }

    /**
     * Check database connection
     */
    protected function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();
            return [
                'status' => 'ok',
                'message' => 'Database connection successful',
                'connection' => config('database.default'),
            ];
        } catch (\Exception $e) {
            Log::error('Database health check failed', ['error' => $e->getMessage()]);
            return [
                'status' => 'error',
                'message' => 'Database connection failed',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check cache system
     */
    protected function checkCache(): array
    {
        try {
            $key = 'health_check_' . time();
            $value = 'test_value';
            
            Cache::put($key, $value, 60);
            $retrieved = Cache::get($key);
            Cache::forget($key);

            if ($retrieved === $value) {
                return [
                    'status' => 'ok',
                    'message' => 'Cache system working',
                    'driver' => config('cache.default'),
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Cache read/write test failed',
                ];
            }
        } catch (\Exception $e) {
            Log::error('Cache health check failed', ['error' => $e->getMessage()]);
            return [
                'status' => 'error',
                'message' => 'Cache system error',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check storage system
     */
    protected function checkStorage(): array
    {
        try {
            $disk = Storage::disk('public');
            $testFile = 'health_check_' . time() . '.txt';
            
            $disk->put($testFile, 'test');
            $exists = $disk->exists($testFile);
            $disk->delete($testFile);

            if ($exists) {
                return [
                    'status' => 'ok',
                    'message' => 'Storage system working',
                    'disk' => 'public',
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Storage read/write test failed',
                ];
            }
        } catch (\Exception $e) {
            Log::error('Storage health check failed', ['error' => $e->getMessage()]);
            return [
                'status' => 'error',
                'message' => 'Storage system error',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check memory usage
     */
    protected function checkMemory(): array
    {
        $memoryUsage = memory_get_usage(true);
        $memoryLimit = $this->parseMemoryLimit(ini_get('memory_limit'));
        $memoryPercent = ($memoryUsage / $memoryLimit) * 100;

        $status = $memoryPercent < 80 ? 'ok' : ($memoryPercent < 90 ? 'warning' : 'error');

        return [
            'status' => $status,
            'message' => "Memory usage: {$memoryPercent}%",
            'usage' => [
                'current' => $this->formatBytes($memoryUsage),
                'limit' => $this->formatBytes($memoryLimit),
                'percent' => round($memoryPercent, 2),
            ],
        ];
    }

    /**
     * Check disk space
     */
    protected function checkDisk(): array
    {
        $freeSpace = disk_free_space(base_path());
        $totalSpace = disk_total_space(base_path());
        
        if ($freeSpace === false || $totalSpace === false) {
            return [
                'status' => 'error',
                'message' => 'Unable to check disk space',
            ];
        }

        $usedSpace = $totalSpace - $freeSpace;
        $usedPercent = ($usedSpace / $totalSpace) * 100;
        $status = $usedPercent < 80 ? 'ok' : ($usedPercent < 90 ? 'warning' : 'error');

        return [
            'status' => $status,
            'message' => "Disk usage: {$usedPercent}%",
            'usage' => [
                'free' => $this->formatBytes($freeSpace),
                'used' => $this->formatBytes($usedSpace),
                'total' => $this->formatBytes($totalSpace),
                'percent' => round($usedPercent, 2),
            ],
        ];
    }

    /**
     * Parse memory limit string to bytes
     */
    protected function parseMemoryLimit($limit): int
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
    protected function formatBytes($bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
