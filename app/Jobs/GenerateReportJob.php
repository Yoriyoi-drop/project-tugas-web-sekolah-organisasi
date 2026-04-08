<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Services\LoggingService;

class GenerateReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 2;

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 600; // 10 minutes

    protected $reportType;
    protected $parameters;
    protected $userId;
    protected $email;

    /**
     * Create a new job instance.
     */
    public function __construct(string $reportType, array $parameters = [], ?int $userId = null, ?string $email = null)
    {
        $this->reportType = $reportType;
        $this->parameters = $parameters;
        $this->userId = $userId;
        $this->email = $email;

        $this->onQueue('reports');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $startTime = microtime(true);

        try {
            LoggingService::logBusinessEvent('report_generation_started', [
                'report_type' => $this->reportType,
                'parameters' => $this->parameters,
                'user_id' => $this->userId,
            ]);

            $reportData = $this->generateReport();

            $filePath = $this->saveReport($reportData);

            LoggingService::logBusinessEvent('report_generation_completed', [
                'report_type' => $this->reportType,
                'file_path' => $filePath,
                'user_id' => $this->userId,
                'duration_ms' => round((microtime(true) - $startTime) * 1000, 2),
            ]);

            // Send notification if email is provided
            if ($this->email) {
                $this->sendReportNotification($filePath);
            }

        } catch (\Exception $e) {
            LoggingService::logError($e, [
                'report_type' => $this->reportType,
                'parameters' => $this->parameters,
                'user_id' => $this->userId,
            ]);

            throw $e;
        }
    }

    /**
     * Generate the report data.
     */
    protected function generateReport(): array
    {
        switch ($this->reportType) {
            case 'students':
                return $this->generateStudentsReport();
            case 'organizations':
                return $this->generateOrganizationsReport();
            case 'activities':
                return $this->generateActivitiesReport();
            case 'attendance':
                return $this->generateAttendanceReport();
            default:
                throw new \Exception("Unknown report type: {$this->reportType}");
        }
    }

    /**
     * Generate students report.
     */
    protected function generateStudentsReport(): array
    {
        $students = \App\Models\Student::query();

        // Apply filters
        if (isset($this->parameters['class'])) {
            $students->where('class', $this->parameters['class']);
        }

        if (isset($this->parameters['date_from'])) {
            $students->whereDate('created_at', '>=', $this->parameters['date_from']);
        }

        if (isset($this->parameters['date_to'])) {
            $students->whereDate('created_at', '<=', $this->parameters['date_to']);
        }

        $data = $students->get()->toArray();

        return [
            'title' => 'Laporan Data Siswa',
            'generated_at' => now()->format('Y-m-d H:i:s'),
            'parameters' => $this->parameters,
            'total_students' => count($data),
            'data' => $data,
        ];
    }

    /**
     * Generate organizations report.
     */
    protected function generateOrganizationsReport(): array
    {
        $organizations = \App\Models\Organization::withCount('members')->get();

        return [
            'title' => 'Laporan Organisasi',
            'generated_at' => now()->format('Y-m-d H:i:s'),
            'total_organizations' => $organizations->count(),
            'data' => $organizations->toArray(),
        ];
    }

    /**
     * Generate activities report.
     */
    protected function generateActivitiesReport(): array
    {
        $activities = \App\Models\OrganizationActivity::with('organization')
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'title' => 'Laporan Kegiatan',
            'generated_at' => now()->format('Y-m-d H:i:s'),
            'total_activities' => $activities->count(),
            'data' => $activities->toArray(),
        ];
    }

    /**
     * Generate attendance report.
     */
    protected function generateAttendanceReport(): array
    {
        $attendances = \App\Models\ActivityRegistration::with(['member.user', 'activity'])
            ->when(isset($this->parameters['date_from']), function ($query) {
                $query->whereDate('created_at', '>=', $this->parameters['date_from']);
            })
            ->when(isset($this->parameters['date_to']), function ($query) {
                $query->whereDate('created_at', '<=', $this->parameters['date_to']);
            })
            ->when(isset($this->parameters['activity_id']), function ($query) {
                $query->where('activity_id', $this->parameters['activity_id']);
            })
            ->get();

        // Group attendances by activity
        $attendancesByActivity = $attendances->groupBy('activity_id');

        $reportData = [];
        foreach ($attendancesByActivity as $activityId => $activityAttendances) {
            $firstAttendance = $activityAttendances->first();
            if ($firstAttendance && $firstAttendance->activity) {
                $activity = $firstAttendance->activity;
                $reportData[] = [
                    'activity' => [
                        'id' => $activity->id,
                        'title' => $activity->title,
                        'start_datetime' => $activity->start_datetime,
                        'end_datetime' => $activity->end_datetime,
                    ],
                    'total_attendees' => $activityAttendances->count(),
                    'confirmed_attendees' => $activityAttendances->where('status', 'confirmed')->count(),
                    'attended_attendees' => $activityAttendances->where('status', 'attended')->count(),
                    'attendees' => $activityAttendances->map(function ($attendance) {
                        return [
                            'member_name' => $attendance->member->user->name ?? 'Unknown',
                            'status' => $attendance->status,
                            'registered_at' => $attendance->created_at,
                            'attended_at' => $attendance->attended_at,
                        ];
                    }),
                ];
            }
        }

        return [
            'title' => 'Laporan Kehadiran',
            'generated_at' => now()->format('Y-m-d H:i:s'),
            'parameters' => $this->parameters,
            'total_activities' => count($reportData),
            'total_attendances' => $attendances->count(),
            'data' => $reportData,
        ];
    }

    /**
     * Save report to file.
     */
    protected function saveReport(array $reportData): string
    {
        $filename = 'report_' . $this->reportType . '_' . now()->format('Y_m_d_His') . '.json';
        $filePath = 'reports/' . $filename;

        \Storage::disk('public')->put($filePath, json_encode($reportData, JSON_PRETTY_PRINT));

        return $filePath;
    }

    /**
     * Send report notification.
     */
    protected function sendReportNotification(string $filePath): void
    {
        // Dispatch email job for report notification
        SendEmailJob::dispatch(
            $this->email,
            \App\Mail\ReportReadyMail::class,
            [
                'report_type' => $this->reportType,
                'download_url' => Storage::disk('public')->url($filePath),
                'generated_at' => now()->format('Y-m-d H:i:s'),
            ]
        );
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        LoggingService::logBusinessEvent('report_generation_failed', [
            'report_type' => $this->reportType,
            'error' => $exception->getMessage(),
            'user_id' => $this->userId,
        ], 'error');
    }
}
