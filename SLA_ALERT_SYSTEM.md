# SLA Alert System Documentation

## Overview
The SLA Alert System provides real-time notifications for SLA (Service Level Agreement) deadlines and incident management. It includes countdown timers, automated alerts, and real-time notification updates.

## Features

### 1. Real-time SLA Countdown
- Live countdown timers for response and resolution deadlines
- Displayed on incident detail pages for clients and employees
- Updates every second with remaining time

### 2. Automated Notifications
- **New Incident Alerts**: Admins receive notifications when clients create new incidents
- **Team Assignment Alerts**: Employees receive notifications when incidents are assigned to their team
- **SLA Deadline Alerts**: Automated alerts at 48h and 24h before deadlines, plus overdue alerts

### 3. Real-time Notification Bell
- Updates notification count every 30 seconds
- Dynamic notification dropdown with latest notifications
- Click to mark as read functionality

## Alert Types

### 1. New Incident Alert
- **Trigger**: When a client creates a new incident
- **Recipients**: All admins
- **Message**: "Nouvel incident créé par [Client Name]: [Incident Title]"

### 2. Team Assignment Alert
- **Trigger**: When an admin assigns an incident to a team
- **Recipients**: All team members
- **Message**: "Nouvel incident assigné à votre équipe: [Incident Title]"

### 3. SLA Deadline Alerts
- **48h Alert**: "⚠️ RAPPEL: Plus que Xh avant le délai de [réponse/résolution] pour l'incident '[Title]' (Priorité: [Priority])"
- **24h Alert**: "⚠️ ALERTE: Plus que Xh avant le délai de [réponse/résolution] pour l'incident '[Title]' (Priorité: [Priority])"
- **Overdue Alert**: "⚠️ URGENT: Délai de [réponse/résolution] dépassé pour l'incident '[Title]' (Priorité: [Priority])"

## Commands

### Check SLA Deadlines
```bash
php artisan sla:check-deadlines
```
- Checks all active incidents for SLA deadlines
- Sends notifications for 48h, 24h, and overdue alerts
- Runs automatically every hour via scheduler

### Test SLA Alerts
```bash
# Test all incidents
php artisan sla:test-alerts

# Test specific incident
php artisan sla:test-alerts --incident-id=1
```

## API Endpoints

### Notification Endpoints
- `GET /notifications/count` - Get unread notification count
- `GET /notifications` - Get recent notifications
- `POST /notifications/mark-read` - Mark notification as read
- `POST /notifications/mark-all-read` - Mark all notifications as read
- `DELETE /notifications/delete` - Delete a notification

## Setup Instructions

### 1. Database Migration
Ensure the notifications table exists with the required fields:
- `user_id` (foreign key to users)
- `sender_id` (foreign key to users, nullable)
- `incident_id` (foreign key to incidents)
- `type` (string)
- `message` (text)
- `is_read` (boolean)

### 2. Schedule the Command
The SLA deadline check is automatically scheduled to run every hour. To manually run it:
```bash
php artisan sla:check-deadlines
```

### 3. Test the System
1. Create a new incident as a client
2. Check that admins receive notifications
3. Assign the incident to a team as admin
4. Check that team members receive notifications
5. Test SLA countdown on incident detail pages

## Files Structure

```
app/
├── Services/
│   └── NotificationService.php      # Core notification logic
├── Http/Controllers/
│   ├── NotificationController.php    # API endpoints for notifications
│   ├── IncidentController.php       # Updated with notification triggers
│   └── AdminController.php          # Updated with team assignment notifications
├── Console/Commands/
│   ├── CheckSlaDeadlines.php       # Scheduled command for SLA checks
│   └── TestSlaAlerts.php           # Test command for debugging
└── Models/
    └── Notification.php             # Notification model

resources/views/partials/
└── notification_bell.blade.php      # Real-time notification bell component
```

## Configuration

### Notification Bell Update Interval
The notification bell updates every 30 seconds. To change this, modify the `setInterval` call in `notification_bell.blade.php`:
```javascript
setInterval(updateNotificationCount, 30000); // 30 seconds
```

### SLA Check Frequency
The SLA deadline check runs every hour. To change this, modify the schedule in `app/Console/Kernel.php`:
```php
$schedule->command('sla:check-deadlines')->hourly();
```

## Troubleshooting

### Notifications Not Appearing
1. Check that the notifications table exists and has the correct structure
2. Verify that the Notification model has `public $timestamps = false;`
3. Check browser console for JavaScript errors in the notification bell

### SLA Countdown Not Working
1. Ensure the incident has an associated project with SLA
2. Check that the SLA has valid `responseTime` and `resolutionTime` values
3. Verify the incident has a valid `creationdate`

### Commands Not Working
1. Ensure the Console Kernel is properly configured
2. Check that all required models and services are imported
3. Verify database connections and table structures 