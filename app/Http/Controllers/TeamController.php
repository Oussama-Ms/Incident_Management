<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    // List all teams
    public function index()
    {
        $teams = Team::all();
        return view('admin.teams', compact('teams'));
    }

    // Show the form to add a new team
    public function create()
    {
        return view('admin.add_team');
    }

    // Store a new team
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'specialization' => 'required|string|max:99',
        ]);
        \App\Models\Team::create($validated);
        return redirect()->route('admin.add.team')->with('success', 'Équipe ajoutée avec succès!');
    }

    // Show the form to edit a team
    public function edit($id)
    {
        $team = Team::findOrFail($id);
        return view('admin.edit_team', compact('team'));
    }

    // Update a team
    public function update(Request $request, $id)
    {
        $team = Team::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'specialization' => 'required|string|max:99',
        ]);
        $team->update($validated);
        return redirect()->route('admin.teams')->with('success', 'Équipe modifiée avec succès!');
    }

    // Delete a team
    public function destroy($id)
    {
        $team = Team::findOrFail($id);
        $team->delete();
        return redirect()->route('admin.teams')->with('success', 'Équipe supprimée avec succès!');
    }
} 