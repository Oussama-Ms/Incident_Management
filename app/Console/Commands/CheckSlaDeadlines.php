<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificationService;

class CheckSlaDeadlines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sla:check-deadlines';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check SLA deadlines and send notifications for incidents';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Checking SLA deadlines...');
        
        try {
            NotificationService::checkSlaDeadlines();
            $this->info('SLA deadline check completed successfully.');
            return 0;
        } catch (\Exception $e) {
            $this->error('Error checking SLA deadlines: ' . $e->getMessage());
            return 1;
        }
    }
} 