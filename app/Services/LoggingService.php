<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;
use Throwable;

class LoggingService
{
    /**
     * Log API requests and responses
     */
    public static function logApiRequest($method, $url, $request = null, $response = null, $userId = null, $duration = null)
    {
        $data = [
            'method' => $method,
            'url' => $url,
            'user_id' => $userId ?? auth()->id(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ];

        if ($request) {
            $data['request'] = $request;
        }

        if ($response) {
            $data['response_status'] = $response->status() ?? null;
            $data['response_size'] = strlen($response->getContent()) ?? 0;
        }

        if ($duration) {
            $data['duration_ms'] = $duration;
        }

        Log::info('API Request', $data);
    }

    /**
     * Log business logic events
     */
    public static function logBusinessEvent($event, $data = [], $level = 'info')
    {
        $logData = array_merge([
            'event' => $event,
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'timestamp' => now()->toISOString(),
        ], $data);

        Log::log($level, "Business Event: {$event}", $logData);
    }

    /**
     * Log performance metrics
     */
    public static function logPerformance($operation, $duration, $metadata = [])
    {
        $data = array_merge([
            'operation' => $operation,
            'duration_ms' => $duration,
            'memory_mb' => memory_get_usage(true) / 1024 / 1024,
            'user_id' => auth()->id(),
        ], $metadata);

        // Log slow operations as warnings
        $level = $duration > 1000 ? 'warning' : 'info';
        
        Log::log($level, "Performance: {$operation}", $data);
    }

    /**
     * Log security events
     */
    public static function logSecurityEvent($event, $data = [], $level = 'warning')
    {
        $logData = array_merge([
            'security_event' => $event,
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toISOString(),
        ], $data);

        Log::log($level, "Security Event: {$event}", $logData);
    }

    /**
     * Log database operations
     */
    public static function logDatabaseOperation($operation, $table, $data = [])
    {
        $logData = array_merge([
            'db_operation' => $operation,
            'table' => $table,
            'user_id' => auth()->id(),
            'timestamp' => now()->toISOString(),
        ], $data);

        Log::info("Database: {$operation} on {$table}", $logData);
    }

    /**
     * Log external API calls
     */
    public static function logExternalApiCall($service, $method, $url, $status = null, $duration = null, $error = null)
    {
        $data = [
            'external_service' => $service,
            'method' => $method,
            'url' => $url,
            'status' => $status,
            'duration_ms' => $duration,
            'user_id' => auth()->id(),
            'timestamp' => now()->toISOString(),
        ];

        if ($error) {
            $data['error'] = $error;
            Log::error("External API Error: {$service}", $data);
        } else {
            Log::info("External API Call: {$service}", $data);
        }
    }

    /**
     * Log cache operations
     */
    public static function logCacheOperation($operation, $key, $hit = null)
    {
        $data = [
            'cache_operation' => $operation,
            'key' => $key,
            'hit' => $hit,
            'timestamp' => now()->toISOString(),
        ];

        Log::debug("Cache: {$operation} {$key}", $data);
    }

    /**
     * Log errors with context
     */
    public static function logError(Exception|Throwable $exception, $context = [])
    {
        $data = array_merge([
            'exception_type' => get_class($exception),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'timestamp' => now()->toISOString(),
        ], $context);

        Log::error('Application Error', $data);
    }

    /**
     * Log user activities for audit trail
     */
    public static function logUserActivity($action, $resource = null, $resourceId = null, $oldValues = null, $newValues = null)
    {
        $data = [
            'action' => $action,
            'resource' => $resource,
            'resource_id' => $resourceId,
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toISOString(),
        ];

        if ($oldValues !== null) {
            $data['old_values'] = $oldValues;
        }

        if ($newValues !== null) {
            $data['new_values'] = $newValues;
        }

        Log::info("User Activity: {$action}", $data);
    }
}
