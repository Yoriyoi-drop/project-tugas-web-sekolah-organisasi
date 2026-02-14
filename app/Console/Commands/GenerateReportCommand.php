<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Report;
use App\Services\ReportGenerator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class GenerateReportCommand extends Command
{
    protected $signature = 'report:generate {type?} {--period=} {--format=pdf}';
    protected $description = 'Generate system reports';

    public function handle()
    {
        $type = $this->argument('type') ?: 'daily';
        $period = $this->option('period') ?: 'today';
        $format = $this->option('format');

        $this->info("Generating {$type} report for {$period} in {$format} format...");

        try {
            // Generate report based on type
            switch ($type) {
                case 'daily':
                    $report = $this->generateDailyReport($period);
                    break;
                case 'weekly':
                    $report = $this->generateWeeklyReport($period);
                    break;
                case 'monthly':
                    $report = $this->generateMonthlyReport($period);
                    break;
                case 'analytics':
                    $report = $this->generateAnalyticsReport($period);
                    break;
                default:
                    $this->error("Invalid report type: {$type}");
                    return 1;
            }

            if ($report) {
                $this->info("Report generated successfully: {$report->file_name}");
                $this->info("Report saved to: {$report->file_path}");
            } else {
                $this->error("Failed to generate report");
                return 1;
            }
        } catch (\Exception $e) {
            $this->error("Error generating report: " . $e->getMessage());
            return 1;
        }

        return 0;
    }

    private function generateDailyReport($period)
    {
        $date = $period === 'today' ? Carbon::today() : Carbon::parse($period);
        
        // Create report data
        $data = [
            'date' => $date->format('Y-m-d'),
            'total_users' => \App\Models\User::whereDate('created_at', $date)->count(),
            'total_organizations' => \App\Models\Organization::whereDate('created_at', $date)->count(),
            'total_posts' => \App\Models\Post::whereDate('created_at', $date)->count(),
            'total_activities' => \App\Models\Activity::whereDate('created_at', $date)->count(),
        ];

        // Generate report file
        $fileName = "daily_report_" . $date->format('Y-m-d') . ".pdf";
        $filePath = "reports/daily/" . $fileName;
        
        // Save to storage
        $content = $this->generatePdfContent($data, 'Daily Report');
        Storage::put($filePath, $content);

        // Create report record
        return Report::create([
            'type' => 'daily',
            'title' => "Daily Report - {$date->format('d M Y')}",
            'description' => "Daily system report for {$date->format('d M Y')}",
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_size' => Storage::size($filePath),
            'generated_by' => 1, // Default admin user
            'generated_at' => now(),
            'expires_at' => $date->addWeek()
        ]);
    }

    private function generateWeeklyReport($period)
    {
        $endDate = $period === 'this_week' ? Carbon::today() : Carbon::parse($period);
        $startDate = $endDate->clone()->subWeek();

        // Create report data
        $data = [
            'period' => $startDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d'),
            'total_users' => \App\Models\User::whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_organizations' => \App\Models\Organization::whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_posts' => \App\Models\Post::whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_activities' => \App\Models\Activity::whereBetween('created_at', [$startDate, $endDate])->count(),
        ];

        // Generate report file
        $fileName = "weekly_report_" . $startDate->format('Y-m-d') . "_" . $endDate->format('Y-m-d') . ".pdf";
        $filePath = "reports/weekly/" . $fileName;
        
        // Save to storage
        $content = $this->generatePdfContent($data, 'Weekly Report');
        Storage::put($filePath, $content);

        // Create report record
        return Report::create([
            'type' => 'weekly',
            'title' => "Weekly Report - {$startDate->format('d M')} to {$endDate->format('d M Y')}",
            'description' => "Weekly system report from {$startDate->format('d M')} to {$endDate->format('d M Y')}",
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_size' => Storage::size($filePath),
            'generated_by' => 1, // Default admin user
            'generated_at' => now(),
            'expires_at' => $endDate->addMonth()
        ]);
    }

    private function generateMonthlyReport($period)
    {
        $date = $period === 'this_month' ? Carbon::today() : Carbon::parse($period);
        $startOfMonth = $date->clone()->startOfMonth();
        $endOfMonth = $date->clone()->endOfMonth();

        // Create report data
        $data = [
            'period' => $startOfMonth->format('Y-m-d') . ' to ' . $endOfMonth->format('Y-m-d'),
            'total_users' => \App\Models\User::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            'total_organizations' => \App\Models\Organization::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            'total_posts' => \App\Models\Post::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            'total_activities' => \App\Models\Activity::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
        ];

        // Generate report file
        $fileName = "monthly_report_" . $startOfMonth->format('Y-m') . ".pdf";
        $filePath = "reports/monthly/" . $fileName;
        
        // Save to storage
        $content = $this->generatePdfContent($data, 'Monthly Report');
        Storage::put($filePath, $content);

        // Create report record
        return Report::create([
            'type' => 'monthly',
            'title' => "Monthly Report - {$startOfMonth->format('M Y')}",
            'description' => "Monthly system report for {$startOfMonth->format('M Y')}",
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_size' => Storage::size($filePath),
            'generated_by' => 1, // Default admin user
            'generated_at' => now(),
            'expires_at' => $endOfMonth->addMonths(3)
        ]);
    }

    private function generateAnalyticsReport($period)
    {
        $date = $period === 'this_month' ? Carbon::today() : Carbon::parse($period);
        $startOfMonth = $date->clone()->startOfMonth();
        $endOfMonth = $date->clone()->endOfMonth();

        // Create detailed analytics report
        $data = [
            'period' => $startOfMonth->format('Y-m-d') . ' to ' . $endOfMonth->format('Y-m-d'),
            'user_growth' => \App\Models\User::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->get(),
            'organization_growth' => \App\Models\Organization::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->get(),
            'top_posts' => \App\Models\Post::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->orderBy('view_count', 'desc')
                ->limit(10)
                ->get(),
            'activity_types' => \App\Models\Activity::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->get(),
        ];

        // Generate report file
        $fileName = "analytics_report_" . $startOfMonth->format('Y-m') . ".pdf";
        $filePath = "reports/analytics/" . $fileName;
        
        // Save to storage
        $content = $this->generateDetailedPdfContent($data, 'Analytics Report');
        Storage::put($filePath, $content);

        // Create report record
        return Report::create([
            'type' => 'analytics',
            'title' => "Analytics Report - {$startOfMonth->format('M Y')}",
            'description' => "Detailed analytics report for {$startOfMonth->format('M Y')}",
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_size' => Storage::size($filePath),
            'generated_by' => 1, // Default admin user
            'generated_at' => now(),
            'expires_at' => $endOfMonth->addMonths(6)
        ]);
    }

    private function generatePdfContent($data, $title)
    {
        // In a real implementation, this would generate an actual PDF
        // For now, we'll return a simple text representation
        $content = "==========================================\n";
        $content .= "           {$title}\n";
        $content .= "==========================================\n";
        $content .= "Generated at: " . now()->format('Y-m-d H:i:s') . "\n";
        $content .= "------------------------------------------\n";

        foreach ($data as $key => $value) {
            if (is_numeric($value)) {
                $content .= ucfirst(str_replace('_', ' ', $key)) . ": {$value}\n";
            } else {
                $content .= ucfirst(str_replace('_', ' ', $key)) . ": {$value}\n";
            }
        }

        $content .= "==========================================\n";
        return $content;
    }

    private function generateDetailedPdfContent($data, $title)
    {
        $content = "==========================================\n";
        $content .= "           {$title}\n";
        $content .= "==========================================\n";
        $content .= "Generated at: " . now()->format('Y-m-d H:i:s') . "\n";
        $content .= "------------------------------------------\n";

        foreach ($data as $key => $value) {
            $content .= ucfirst(str_replace('_', ' ', $key)) . ":\n";
            if (is_array($value) || is_object($value)) {
                foreach ($value as $item) {
                    if (is_object($item)) {
                        $content .= "  - " . json_encode($item) . "\n";
                    } else {
                        $content .= "  - " . $item . "\n";
                    }
                }
            } else {
                $content .= "  {$value}\n";
            }
            $content .= "\n";
        }

        $content .= "==========================================\n";
        return $content;
    }
}
