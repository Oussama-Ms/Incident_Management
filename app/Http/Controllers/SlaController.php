<?php

namespace App\Http\Controllers;

use App\Models\Sla;
use Illuminate\Http\Request;

class SlaController extends Controller
{
    // List all SLAs
    public function index()
    {
        $slas = Sla::all();
        return view('admin.slas', compact('slas'));
    }

    // Show the form to add a new SLA
    public function create()
    {
        return view('admin.add_sla');
    }

    // Store a new SLA
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'duration' => 'required|integer|min:1',
        ]);
        Sla::create($validated);
        return redirect()->route('admin.add.sla')->with('success', 'SLA ajouté avec succès!');
    }
} 