<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // List all clients
    public function index()
    {
        $clients = User::where('role', 'client')->get();
        return view('admin.clients', compact('clients'));
    }

    // Show the form to add a new client
    public function create()
    {
        return view('admin.add_client');
    }

    // Store a new client
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|string|min:6|confirmed',
        ]);
        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = bcrypt($validated['password']);
        $user->role = 'client';
        $user->save();
        return redirect()->route('admin.add.client')->with('success', 'Client ajouté avec succès!');
    }

    // Show the form to edit a client
    public function edit($id)
    {
        $client = User::where('role', 'client')->findOrFail($id);
        return view('admin.edit_client', compact('client'));
    }

    // Update a client
    public function update(Request $request, $id)
    {
        $client = User::where('role', 'client')->findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:user,email,' . $client->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);
        $client->name = $validated['name'];
        $client->email = $validated['email'];
        if (!empty($validated['password'])) {
            $client->password = bcrypt($validated['password']);
        }
        $client->save();
        return redirect()->route('admin.clients')->with('success', 'Client modifié avec succès!');
    }

    // Delete a client
    public function destroy($id)
    {
        $client = User::where('role', 'client')->findOrFail($id);
        $client->delete();
        return redirect()->route('admin.clients')->with('success', 'Client supprimé avec succès!');
    }
} 