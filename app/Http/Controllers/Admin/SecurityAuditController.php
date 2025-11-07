<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\SecurityAuditRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SecurityAuditController extends Controller
{
    protected $repository;

    public function __construct(SecurityAuditRepository $repository)
    {
        // Use fully-qualified middleware class with parameter to avoid alias resolution issues
        $this->middleware(['auth', \App\Http\Middleware\CheckAbility::class . ':manage_security']);
        $this->repository = $repository;
    }

    /**
     * Display the security audit dashboard.
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $filters = $request->only([
            'user_id',
            'ip_address',
            'status',
            'action',
            'risk_level',
            'date_from',
            'date_to'
        ]);

        // Get paginated logs with filters
        $logs = $this->repository->getPaginatedLogs($filters);

        // Get summary statistics
        $stats = $this->repository->getSummaryStats();

        // Get event types for filter dropdown
    $eventTypes = $this->repository->getEventTypes();

        // Get recent high-risk events
        $recentHighRiskEvents = $this->repository->getRecentHighRiskEvents();

        return view('admin.security.audit', compact(
            'logs',
            'stats',
            'eventTypes',
            'recentHighRiskEvents',
            'filters'
        ));
    }

    /**
     * Export security logs to CSV.
     */
    public function export(Request $request)
    {
        $filters = $request->only([
            'user_id',
            'ip_address',
            'status',
            'action',
            'risk_level',
            'date_from',
            'date_to'
        ]);

        $logs = $this->repository->getPaginatedLogs($filters)->items();

        // Build CSV into a string so we can set exact headers (no charset appended)
        $fh = fopen('php://temp', 'r+');

        // Add headers
        fputcsv($fh, [
            'ID',
            'User',
            'Action',
            'Description',
            'IP Address',
            'Risk Level',
            'Status',
            'Created At'
        ]);

        foreach ($logs as $log) {
            $description = '';
            $status = '';
            if (is_array($log->data)) {
                $description = $log->data['description'] ?? '';
                $status = $log->data['status'] ?? '';
            }

            fputcsv($fh, [
                $log->id,
                $log->user->name ?? 'N/A',
                $log->action,
                $description,
                $log->ip_address,
                $log->risk_level,
                $status,
                $log->created_at
            ]);
        }

        rewind($fh);
        $csv = stream_get_contents($fh);
        fclose($fh);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="security-logs.csv"',
        ];

        // Use Symfony Response directly so we can control the charset behavior
        $symfonyResponse = new \Symfony\Component\HttpFoundation\Response($csv, 200, $headers);

        try {
            $ref = new \ReflectionObject($symfonyResponse);
            if ($ref->hasProperty('charset')) {
                $prop = $ref->getProperty('charset');
                $prop->setAccessible(true);
                $prop->setValue($symfonyResponse, null);
            }
        } catch (\Throwable $e) {
            // ignore
        }

        // Debugging: log headers and charset to help tests that assert exact header values
        try {
            Log::info('CSV export headers', ['headers' => $symfonyResponse->headers->all(), 'charset' => $symfonyResponse->getCharset()]);
        } catch (\Throwable $e) {
            // ignore logging failures
        }

        return $symfonyResponse;
    }
}
