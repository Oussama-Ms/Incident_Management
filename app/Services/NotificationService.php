<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Incident;
use App\Models\Projet;
use Carbon\Carbon;

class NotificationService
{
    /**
     * Send notification to admin when new incident is created
     */
    public static function notifyAdminNewIncident(Incident $incident)
    {
        $admins = User::where('role', 'admin')->get();
        
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'sender_id' => $incident->user_id,
                'incident_id' => $incident->id,
                'type' => 'new_incident',
                'message' => 'Nouvel incident créé par ' . $incident->user->name . ': ' . $incident->title,
                'is_read' => false,
            ]);
        }
    }

    /**
     * Send notification to employee when incident is assigned to their team
     */
    public static function notifyEmployeeIncidentAssigned(Incident $incident)
    {
        if (!$incident->projet || !$incident->projet->team) {
            return;
        }

        $teamMembers = User::whereHas('employee', function($query) use ($incident) {
            $query->where('team_id', $incident->projet->team_id);
        })->get();

        foreach ($teamMembers as $employee) {
            Notification::create([
                'user_id' => $employee->id,
                'sender_id' => auth()->id(),
                'incident_id' => $incident->id,
                'type' => 'incident_assigned',
                'message' => 'Nouvel incident assigné à votre équipe: ' . $incident->title,
                'is_read' => false,
            ]);
        }
    }

    /**
     * Check and send SLA deadline alerts
     */
    public static function checkSlaDeadlines()
    {
        $incidents = Incident::with(['projet.sla', 'user'])
            ->whereIn('status', ['Nouveau', 'En cours', 'En attente'])
            ->get();

        foreach ($incidents as $incident) {
            if (!$incident->projet || !$incident->projet->sla) {
                continue;
            }

            $sla = $incident->projet->sla;
            $creationDate = Carbon::parse($incident->creationdate);
            $responseDeadline = $creationDate->addHours($sla->responseTime);
            $resolutionDeadline = $creationDate->addHours($sla->resolutionTime);
            
            $now = Carbon::now();
            
            // Check response deadline (48h and 24h alerts)
            if ($now->lt($responseDeadline)) {
                $hoursLeft = $now->diffInHours($responseDeadline, false);
                
                if ($hoursLeft <= 48 && $hoursLeft > 24) {
                    self::sendSlaAlert($incident, 'response', 48, $hoursLeft);
                } elseif ($hoursLeft <= 24 && $hoursLeft > 0) {
                    self::sendSlaAlert($incident, 'response', 24, $hoursLeft);
                }
            } else {
                // Response deadline passed
                self::sendSlaAlert($incident, 'response', 0, 0);
            }
            
            // Check resolution deadline (48h and 24h alerts)
            if ($now->lt($resolutionDeadline)) {
                $hoursLeft = $now->diffInHours($resolutionDeadline, false);
                
                if ($hoursLeft <= 48 && $hoursLeft > 24) {
                    self::sendSlaAlert($incident, 'resolution', 48, $hoursLeft);
                } elseif ($hoursLeft <= 24 && $hoursLeft > 0) {
                    self::sendSlaAlert($incident, 'resolution', 24, $hoursLeft);
                }
            } else {
                // Resolution deadline passed
                self::sendSlaAlert($incident, 'resolution', 0, 0);
            }
        }
    }

    /**
     * Send SLA deadline alert
     */
    private static function sendSlaAlert(Incident $incident, $type, $threshold, $hoursLeft)
    {
        $typeText = $type === 'response' ? 'réponse' : 'résolution';
        $priority = $incident->projet->sla->priority;
        
        // Notify admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $message = self::getSlaAlertMessage($incident, $type, $threshold, $hoursLeft);
            
            Notification::create([
                'user_id' => $admin->id,
                'sender_id' => null,
                'incident_id' => $incident->id,
                'type' => 'sla_alert',
                'message' => $message,
                'is_read' => false,
            ]);
        }
        
        // Notify team members if incident is assigned
        if ($incident->projet && $incident->projet->team) {
            $teamMembers = User::whereHas('employee', function($query) use ($incident) {
                $query->where('team_id', $incident->projet->team_id);
            })->get();

            foreach ($teamMembers as $employee) {
                $message = self::getSlaAlertMessage($incident, $type, $threshold, $hoursLeft);
                
                Notification::create([
                    'user_id' => $employee->id,
                    'sender_id' => null,
                    'incident_id' => $incident->id,
                    'type' => 'sla_alert',
                    'message' => $message,
                    'is_read' => false,
                ]);
            }
        }
    }

    /**
     * Get SLA alert message
     */
    private static function getSlaAlertMessage(Incident $incident, $type, $threshold, $hoursLeft)
    {
        $typeText = $type === 'response' ? 'réponse' : 'résolution';
        $priority = $incident->projet->sla->priority;
        
        if ($threshold === 0) {
            return "⚠️ URGENT: Délai de {$typeText} dépassé pour l'incident '{$incident->title}' (Priorité: {$priority})";
        } elseif ($threshold === 24) {
            return "⚠️ ALERTE: Plus que {$hoursLeft}h avant le délai de {$typeText} pour l'incident '{$incident->title}' (Priorité: {$priority})";
        } else {
            return "⚠️ RAPPEL: Plus que {$hoursLeft}h avant le délai de {$typeText} pour l'incident '{$incident->title}' (Priorité: {$priority})";
        }
    }

    /**
     * Get SLA countdown for an incident
     */
    public static function getSlaCountdown(Incident $incident)
    {
        if (!$incident->projet || !$incident->projet->sla) {
            return null;
        }

        $sla = $incident->projet->sla;
        $creationDate = Carbon::parse($incident->creationdate);
        $now = Carbon::now();
        
        $responseDeadline = $creationDate->copy()->addHours($sla->responseTime);
        $resolutionDeadline = $creationDate->copy()->addHours($sla->resolutionTime);
        
        $responseHoursLeft = $now->diffInHours($responseDeadline, false);
        $resolutionHoursLeft = $now->diffInHours($resolutionDeadline, false);
        
        return [
            'response' => [
                'deadline' => $responseDeadline,
                'hours_left' => $responseHoursLeft,
                'is_overdue' => $responseHoursLeft < 0
            ],
            'resolution' => [
                'deadline' => $resolutionDeadline,
                'hours_left' => $resolutionHoursLeft,
                'is_overdue' => $resolutionHoursLeft < 0
            ]
        ];
    }
} 