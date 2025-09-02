<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificationService;
use App\Models\Incident;

class TestSlaAlerts extends Command
{
    protected $signature = 'sla:test-alerts {--incident-id= : Specific incident ID to test}';
    protected $description = 'Test SLA alerts for incidents';

    public function handle()
    {
        $incidentId = $this->option('incident-id');
        
        if ($incidentId) {
            $incident = Incident::with(['projet.sla', 'user'])->find($incidentId);
            if (!$incident) {
                $this->error("Incident with ID {$incidentId} not found.");
                return 1;
            }
            
            $this->info("Testing SLA alerts for incident: {$incident->title}");
            $countdown = NotificationService::getSlaCountdown($incident);
            
            if ($countdown) {
                $this->info("Response deadline: " . $countdown['response']['deadline']);
                $this->info("Response hours left: " . $countdown['response']['hours_left']);
                $this->info("Resolution deadline: " . $countdown['resolution']['deadline']);
                $this->info("Resolution hours left: " . $countdown['resolution']['hours_left']);
            } else {
                $this->warn("No SLA found for this incident.");
            }
        } else {
            $this->info("Running SLA deadline check...");
            NotificationService::checkSlaDeadlines();
            $this->info("SLA deadline check completed.");
        }
        
        return 0;
    }
} 