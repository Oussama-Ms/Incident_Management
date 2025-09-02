<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Incident;
use App\Models\User;
use App\Models\Team;
use App\Models\Projet;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // Show dashboard with statistics
    public function dashboard(Request $request)
    {
        // Real statistics
        $totalIncidents = \App\Models\Incident::count();
        $openCount = \App\Models\Incident::where('status', 'Nouveau')->count();
        $inProgressCount = \App\Models\Incident::where('status', 'En cours')->count();
        $resolvedCount = \App\Models\Incident::where('status', 'Résolu')->count();
        $closedCount = \App\Models\Incident::where('status', 'Fermé')->count();

        // New stats
        $clientCount = \App\Models\User::where('role', 'client')->count();
        $employeeCount = \App\Models\User::where('role', 'employee')->count();
        $teamCount = \App\Models\Team::count();
        $projectCount = \App\Models\Projet::count();
        $adminCount = \App\Models\User::where('role', 'admin')->count();

        // Incidents by month (last 12 months)
        $incidentsByMonth = \App\Models\Incident::selectRaw('DATE_FORMAT(creationdate, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->take(12)
            ->get();

        // Incidents by team
        $incidentsByTeam = DB::table('incident')
            ->join('projet', 'incident.projet_id', '=', 'projet.id')
            ->join('team', 'projet.team_id', '=', 'team.id')
            ->select('team.name as team', DB::raw('COUNT(incident.id) as count'))
            ->groupBy('team.name')
            ->get();

        return view('dashboards.admin', compact(
            'totalIncidents', 'openCount', 'inProgressCount', 'resolvedCount', 'closedCount',
            'clientCount', 'employeeCount', 'teamCount', 'projectCount', 'adminCount',
            'incidentsByMonth', 'incidentsByTeam'
        ));
    }

    // Show incidents management page
    public function incidents(Request $request)
    {
        $incidents = Incident::with(['user', 'projet.team'])->orderByDesc('creationdate')->get();
        $teams = Team::all();
        return view('admin.incidents', compact('incidents', 'teams'));
    }

    // Show admin profile page
    public function profile(Request $request)
    {
        $user = Auth::user();
        $incidentCount = Incident::count();
        return view('admin.profile', compact('user', 'incidentCount'));
    }

    public function show($id)
    {
        $incident = \App\Models\Incident::with(['comments.user', 'files', 'user', 'projet'])->findOrFail($id);
        return view('admin.incident_show', compact('incident'));
    }

    // List all admins
    public function adminIndex()
    {
        $admins = \App\Models\User::where('role', 'admin')->get();
        return view('admin.admins', compact('admins'));
    }

    // Show the form to add a new admin
    public function adminCreate()
    {
        return view('admin.add_admin');
    }

    // Store a new admin
    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|string|min:6|confirmed',
        ]);
        $user = new \App\Models\User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = bcrypt($validated['password']);
        $user->role = 'admin';
        $user->save();
        return redirect()->route('admin.add.admin')->with('success', 'Admin ajouté avec succès!');
    }

    // Show the form to edit an admin
    public function adminEdit($id)
    {
        $admin = \App\Models\User::where('role', 'admin')->findOrFail($id);
        return view('admin.edit_admin', compact('admin'));
    }

    // Update an admin
    public function adminUpdate(Request $request, $id)
    {
        $admin = \App\Models\User::where('role', 'admin')->findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:user,email,' . $admin->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);
        $admin->name = $validated['name'];
        $admin->email = $validated['email'];
        if (!empty($validated['password'])) {
            $admin->password = bcrypt($validated['password']);
        }
        $admin->save();
        return redirect()->route('admin.admins')->with('success', 'Admin modifié avec succès!');
    }

    // Delete an admin
    public function adminDestroy($id)
    {
        $admin = \App\Models\User::where('role', 'admin')->findOrFail($id);
        $admin->delete();
        return redirect()->route('admin.admins')->with('success', 'Admin supprimé avec succès!');
    }

    // Assign team to incident
    public function assignTeam(Request $request, $id)
    {
        $request->validate([
            'team_id' => 'required|exists:team,id'
        ]);

        $incident = Incident::with('projet')->findOrFail($id);
        
        // Update the project's team assignment
        if ($incident->projet) {
            $incident->projet->team_id = $request->team_id;
            $incident->projet->save();
            
            // Send notification to team members
            NotificationService::notifyEmployeeIncidentAssigned($incident);
        }

        return redirect()->back()->with('success', 'Équipe assignée avec succès!');
    }

    // Delete incident
    public function deleteIncident($id)
    {
        $incident = Incident::findOrFail($id);
        
        // Delete related files
        foreach ($incident->files as $file) {
            $filePath = storage_path('app/public/' . $file->file_path);
            if (file_exists($filePath) && is_file($filePath)) {
                unlink($filePath);
            }
            $file->delete();
        }
        
        // Delete related comments
        $incident->comments()->delete();
        
        // Delete related notifications
        \App\Models\Notification::where('incident_id', $incident->id)->delete();
        
        // Delete the incident
        $incident->delete();
        
        return redirect()->route('admin.incidents')->with('success', 'Incident supprimé avec succès!');
    }

    // Export data functionality
    public function exportData($type, $format)
    {
        $data = [];
        $filename = '';
        
        switch ($type) {
            case 'incidents':
                $data = \App\Models\Incident::with(['user', 'projet.team'])->get()->map(function ($incident) {
                    return [
                        'ID' => $incident->id,
                        'Titre' => $incident->title,
                        'Description' => $incident->description,
                        'Statut' => $incident->status,
                        'Priorité' => $incident->priority,
                        'Catégorie' => $incident->category,
                        'Client' => $incident->user->name ?? 'N/A',
                        'Projet' => $incident->projet->nom ?? 'N/A',
                        'Équipe' => $incident->projet->team->name ?? 'Non assignée',
                        'Date de création' => $incident->creationdate ? $incident->creationdate->format('d/m/Y H:i') : 'N/A',
                        'Date de résolution' => $incident->resolveddate ? $incident->resolveddate->format('d/m/Y H:i') : 'N/A',
                    ];
                });
                $filename = 'incidents_' . date('Y-m-d_H-i-s');
                break;
                
            case 'clients':
                $data = \App\Models\User::where('role', 'client')->get()->map(function ($client) {
                    return [
                        'ID' => $client->id,
                        'Nom' => $client->name,
                        'Email' => $client->email,
                        'Téléphone' => $client->phone ?? 'N/A',
                        'Date de création' => $client->created_at ? $client->created_at->format('d/m/Y H:i') : 'N/A',
                    ];
                });
                $filename = 'clients_' . date('Y-m-d_H-i-s');
                break;
                
            case 'employees':
                $data = \App\Models\Employee::with(['user', 'team'])->get()->map(function ($employee) {
                    return [
                        'ID' => $employee->id,
                        'Nom' => $employee->user->name ?? 'N/A',
                        'Email' => $employee->user->email ?? 'N/A',
                        'Téléphone' => $employee->user->phone ?? 'N/A',
                        'Équipe' => $employee->team->name ?? 'Non assigné',
                        'Spécialisation' => $employee->specialization ?? 'N/A',
                        'Rôle' => $employee->role ?? 'N/A',
                    ];
                });
                $filename = 'employees_' . date('Y-m-d_H-i-s');
                break;
                
            case 'projects':
                $data = \App\Models\Projet::with(['client', 'team', 'sla'])->get()->map(function ($project) {
                    return [
                        'ID' => $project->id,
                        'Nom' => $project->nom,
                        'Description' => $project->description,
                        'Client' => $project->client->name ?? 'N/A',
                        'Équipe' => $project->team->name ?? 'Non assignée',
                        'Statut' => $project->status,
                        'Date de début' => $project->startDate ? \Carbon\Carbon::parse($project->startDate)->format('d/m/Y') : 'N/A',
                        'Date de fin' => $project->endDate ? \Carbon\Carbon::parse($project->endDate)->format('d/m/Y') : 'N/A',
                        'Délai de réponse SLA (h)' => $project->sla->responseTime ?? 'N/A',
                        'Délai de résolution SLA (h)' => $project->sla->resolutionTime ?? 'N/A',
                    ];
                });
                $filename = 'projects_' . date('Y-m-d_H-i-s');
                break;
                
            default:
                abort(404, 'Type de données non supporté');
        }
        
        if ($format === 'excel') {
            return $this->exportToExcel($data, $filename);
        } elseif ($format === 'pdf') {
            return $this->exportToPdf($data, $filename, $type);
        } else {
            abort(400, 'Format non supporté');
        }
    }
    
    private function exportToExcel($data, $filename)
    {
        // Create CSV content (Excel compatible)
        $headers = array_keys($data->first() ?: []);
        $csv = fopen('php://temp', 'r+');
        
        // Add BOM for Excel UTF-8 compatibility
        fwrite($csv, "\xEF\xBB\xBF");
        
        // Add headers
        fputcsv($csv, $headers, ';');
        
        // Add data
        foreach ($data as $row) {
            fputcsv($csv, $row, ';');
        }
        
        rewind($csv);
        $content = stream_get_contents($csv);
        fclose($csv);
        
        return response($content)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '.csv"');
    }
    
    private function exportToPdf($data, $filename, $type)
    {
        // Simple HTML to PDF conversion
        $html = '<html><head><meta charset="UTF-8"><style>';
        $html .= 'body { font-family: Arial, sans-serif; margin: 20px; }';
        $html .= 'table { width: 100%; border-collapse: collapse; margin-top: 20px; }';
        $html .= 'th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }';
        $html .= 'th { background-color: #5B307E; color: white; }';
        $html .= 'tr:nth-child(even) { background-color: #f2f2f2; }';
        $html .= 'h1 { color: #5B307E; text-align: center; }';
        $html .= '</style></head><body>';
        
        $html .= '<h1>Export ' . ucfirst($type) . ' - ' . date('d/m/Y H:i') . '</h1>';
        
        if ($data->isNotEmpty()) {
            $headers = array_keys($data->first());
            $html .= '<table><thead><tr>';
            foreach ($headers as $header) {
                $html .= '<th>' . htmlspecialchars($header) . '</th>';
            }
            $html .= '</tr></thead><tbody>';
            
            foreach ($data as $row) {
                $html .= '<tr>';
                foreach ($row as $value) {
                    $html .= '<td>' . htmlspecialchars($value) . '</td>';
                }
                $html .= '</tr>';
            }
            $html .= '</tbody></table>';
        } else {
            $html .= '<p>Aucune donnée à exporter.</p>';
        }
        
        $html .= '</body></html>';
        
        // For now, return HTML (you can integrate a proper PDF library like DomPDF later)
        return response($html)
            ->header('Content-Type', 'text/html; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '.html"');
    }
} 