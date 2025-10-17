<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendNotificationCommand extends Command
{
    protected $signature = 'notify:send';
    protected $description = 'Send notifications (placeholder)';

    public function handle()
    {
        $this->info('Notifications sent (placeholder).');
        return 0;
    }
}
