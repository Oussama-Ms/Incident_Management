<?php

namespace App\Http\Controllers;

use App\Models\Projet;
use Illuminate\Http\Request;

class ProjetController extends Controller
{
    // Show the form to add a new project
    public function create()
    {
        $clients = \App\Models\User::where('role', 'client')->get();
        $teams = \App\Models\Team::all();
        return view('admin.add_project', compact('clients', 'teams'));
    }

    // Store a new project
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'responseTime' => 'required|integer|min:1',
            'resolutionTime' => 'required|integer|min:1',
            'priority' => 'required|string|max:50',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'status' => 'required|string|max:30',
            'Client_id' => 'required|exists:user,id',
            'team_id' => 'required|exists:team,id',
        ]);
        $sla = \App\Models\Sla::create([
            'responseTime' => $validated['responseTime'],
            'resolutionTime' => $validated['resolutionTime'],
            'priority' => $validated['priority'],
        ]);
        $projet = \App\Models\Projet::create([
            'nom' => $validated['nom'],
            'description' => $validated['description'] ?? null,
            'sla_id' => $sla->id,
            'startDate' => $validated['startDate'],
            'endDate' => $validated['endDate'],
            'status' => $validated['status'],
            'Client_id' => $validated['Client_id'],
            'team_id' => $validated['team_id'],
        ]);
        return redirect()->route('admin.add.project')->with('success', 'Projet et SLA ajoutés avec succès!');
    }

    // List all projects
    public function index()
    {
        $projects = Projet::all();
        return view('admin.projects', compact('projects'));
    }

    // Show the form to edit a project
    public function edit($id)
    {
        $project = \App\Models\Projet::with('sla')->findOrFail($id);
        $clients = \App\Models\User::where('role', 'client')->get();
        $teams = \App\Models\Team::all();
        return view('admin.edit_project', compact('project', 'clients', 'teams'));
    }

    // Update a project
    public function update(Request $request, $id)
    {
        $project = \App\Models\Projet::with('sla')->findOrFail($id);
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'responseTime' => 'required|integer|min:1',
            'resolutionTime' => 'required|integer|min:1',
            'priority' => 'required|string|max:50',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'status' => 'required|string|max:30',
            'Client_id' => 'required|exists:user,id',
            'team_id' => 'required|exists:team,id',
        ]);
        $project->nom = $validated['nom'];
        $project->description = $validated['description'] ?? null;
        $project->startDate = $validated['startDate'];
        $project->endDate = $validated['endDate'];
        $project->status = $validated['status'];
        $project->Client_id = $validated['Client_id'];
        $project->team_id = $validated['team_id'];
        $project->save();
        // Update SLA
        if ($project->sla) {
            $project->sla->responseTime = $validated['responseTime'];
            $project->sla->resolutionTime = $validated['resolutionTime'];
            $project->sla->priority = $validated['priority'];
            $project->sla->save();
        }
        return redirect()->route('admin.projects')->with('success', 'Projet mis à jour avec succès!');
    }

    // Delete a project
    public function destroy($id)
    {
        $project = \App\Models\Projet::findOrFail($id);
        $project->delete();
        return redirect()->route('admin.projects')->with('success', 'Projet supprimé avec succès!');
    }
} 