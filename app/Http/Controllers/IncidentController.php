<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use App\Models\Projet;
use App\Models\File;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IncidentController extends Controller
{
    // Show the form to create a new incident
    public function create()
    {
        $client = Auth::user();
        $projects = Projet::where('Client_id', $client->id)->with('sla')->get();
        return view('dashboards.client_add_incident', compact('projects'));
    }

    // Store a new incident
    public function store(Request $request)
    {
        $client = Auth::user();
        $validated = $request->validate([
            'title' => 'required|string|max:99',
            'description' => 'required|string|max:999',
            'projet_id' => 'required|exists:projet,id',
            'priority' => 'required|string|max:20',
            'category' => 'nullable|string|max:50',
            'contact_phone' => 'nullable|string|max:30',
            'notes' => 'nullable|string',
            'media.*' => 'nullable|file|mimes:jpeg,png,pdf,jpg,gif,doc,docx,txt|max:20480', // 20MB max per file
        ]);

        $incident = Incident::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'category' => $validated['category'] ?? null,
            'contact_phone' => $validated['contact_phone'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => 'Nouveau',
            'creationdate' => now(),
            'updatedate' => now(),
            'resolveddate' => null,
            'user_id' => $client->id,
            'projet_id' => $validated['projet_id'],
        ]);

        // Handle file uploads
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('incident_files', 'public');
                File::create([
                    'filename' => $path,
                    'size' => $file->getSize(),
                    'uploaddate' => now(),
                    'user-id' => $client->id,
                    'incident-id' => $incident->id,
                ]);
            }
        }

        // Send notification to admin about new incident
        NotificationService::notifyAdminNewIncident($incident);

        return redirect()->route('incidents.index')->with('success', 'Incident créé avec succès!');
    }

    // List the client's incidents
    public function index(Request $request)
    {
        $client = Auth::user();
        $query = Incident::with('projet', 'files')
            ->where('user_id', $client->id);

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhereHas('projet', function($q2) use ($search) {
                      $q2->where('nom', 'like', "%$search%")
                        ->orWhere('description', 'like', "%$search%")
                        ;
                  });
            });
        }
        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        // Apply date filter
        if ($request->filled('date')) {
            $query->whereDate('creationdate', $request->input('date'));
        }
        $incidents = $query->orderByDesc('creationdate')->get();
        return view('dashboards.client_incidents', compact('incidents'));
    }

    // Show a single incident with comments
    public function show($id)
    {
        $incident = Incident::with(['projet', 'files', 'user', 'comments.user'])->findOrFail($id);
        // Mark related notifications as read for this user and incident
        if (auth()->check()) {
            \App\Models\Notification::where('user_id', auth()->id())
                ->where('incident_id', $id)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }
        return view('dashboards.incident_show', compact('incident'));
    }

    // List all incidents for employees
    public function employeeIndex(Request $request)
    {
        // Get the current employee's team
        $employee = Auth::user();
        $employeeTeam = $employee->employee->team ?? null;
        
        if (!$employeeTeam) {
            // If employee has no team, show no incidents
            $incidents = collect([]);
            return view('dashboards.employee_incidents', compact('incidents'));
        }
        
        // Only show incidents assigned to the employee's team
        $query = Incident::with(['projet', 'files', 'user'])
            ->whereHas('projet', function($q) use ($employeeTeam) {
                $q->where('team_id', $employeeTeam->id);
            });
            
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhereHas('projet', function($q2) use ($search) {
                      $q2->where('nom', 'like', "%$search%")
                        ->orWhere('description', 'like', "%$search%")
                        ;
                  })
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ;
                  });
            });
        }
        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        // Apply date filter
        if ($request->filled('date')) {
            $query->whereDate('creationdate', $request->input('date'));
        }
        $incidents = $query->orderByDesc('creationdate')->get();
        return view('dashboards.employee_incidents', compact('incidents'));
    }

    // Show a single incident (employee view)
    public function employeeShow($id)
    {
        // Get the current employee's team
        $employee = Auth::user();
        $employeeTeam = $employee->employee->team ?? null;
        
        if (!$employeeTeam) {
            abort(403, 'Vous n\'avez pas d\'équipe assignée.');
        }
        
        // Only allow access to incidents assigned to the employee's team
        $incident = Incident::with(['projet', 'files', 'user', 'comments.user'])
            ->whereHas('projet', function($q) use ($employeeTeam) {
                $q->where('team_id', $employeeTeam->id);
            })
            ->findOrFail($id);
            
        // Mark related notifications as read for this user and incident (employee side)
        if (auth()->check()) {
            \App\Models\Notification::where('user_id', auth()->id())
                ->where('incident_id', $id)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }
        return view('dashboards.employee_incident_show', compact('incident'));
    }

    // Update incident status (employee action)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|max:50',
        ]);
        $incident = Incident::findOrFail($id);
        $incident->status = $request->input('status');
        $incident->updatedate = now();
        $incident->save();
        return back()->with('success', 'Statut de l\'incident mis à jour.');
    }

    // Employee contacts client (email + notification)
    public function contactClient(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:999',
        ]);
        $incident = Incident::with('user')->findOrFail($id);
        $client = $incident->user;
        $employee = Auth::user();

        // Create notification for client
        \App\Models\Notification::create([
            'user_id' => $client->id,
            'sender_id' => $employee->id,
            'incident_id' => $incident->id,
            'type' => 'employee_contact',
            'message' => 'Message de ' . $employee->name . ': "' . $request->input('message') . '" concernant l\'incident: ' . $incident->title,
            'is_read' => false,
        ]);

        // Create a comment for the conversation thread
        $comment = \App\Models\Comment::create([
            'content' => $request->input('message'),
            'user_id' => $employee->id,
            'incident_id' => $incident->id,
        ]);

        // Send email to client
        \Mail::send([], [], function ($message) use ($client, $employee, $incident, $request) {
            $body = '<p>Bonjour ' . $client->name . ',</p>' .
                '<p><strong>' . $employee->name . '</strong> vous a envoyé un message concernant votre incident : <strong>' . $incident->title . '</strong>.</p>' .
                '<blockquote style="background:#f9f9f9;padding:1em;border-left:4px solid #5B307E;">' . nl2br(e($request->input('message'))) . '</blockquote>' .
                '<p><a href="' . url('/incidents/' . $incident->id) . '" style="color:#5B307E;font-weight:bold;">Voir l\'incident</a></p>';
            $message->to($client->email)
                ->subject('Nouveau message concernant votre incident: ' . $incident->title)
                ->html($body);
        });

        return back()->with('success', 'Le client a été contacté avec succès.');
    }
}
