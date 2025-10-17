<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateReportCommand extends Command
{
    protected $signature = 'report:generate';
    protected $description = 'Generate a sample report (placeholder)';

    public function handle()
    {
        $this->info('Report generated (placeholder).');
        return 0;
    }
}
