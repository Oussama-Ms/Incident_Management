<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\Team;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    // List all employees
    public function index()
    {
        $employees = User::where('role', 'employee')->get();
        return view('admin.employees', compact('employees'));
    }

    // Show the form to add a new employee
    public function create()
    {
        $teams = Team::all();
        return view('admin.add_employee', compact('teams'));
    }

    // Store a new employee
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|string|min:6|confirmed',
            'team_id' => 'nullable|exists:team,id',
        ]);
        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = bcrypt($validated['password']);
        $user->role = 'employee';
        $user->save();
        Employee::create([
            'user_id' => $user->id,
            'team_id' => $request->team_id, // will be null if not selected
            'role' => 'employee',
        ]);
        return redirect()->route('admin.add.employee')->with('success', 'Employé ajouté avec succès!');
    }

    // Show the form to edit an employee
    public function edit($id)
    {
        $employee = User::where('role', 'employee')->with('employee')->findOrFail($id);
        $teams = Team::all();
        return view('admin.edit_employee', compact('employee', 'teams'));
    }

    // Update an employee
    public function update(Request $request, $id)
    {
        $employee = User::where('role', 'employee')->with('employee')->findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:user,email,' . $employee->id,
            'password' => 'nullable|string|min:6|confirmed',
            'team_id' => 'nullable|exists:team,id',
        ]);
        $employee->name = $validated['name'];
        $employee->email = $validated['email'];
        if (!empty($validated['password'])) {
            $employee->password = bcrypt($validated['password']);
        }
        $employee->save();
        // Update team in Employee model
        if ($employee->employee) {
            $employee->employee->team_id = $request->team_id;
            $employee->employee->save();
        }
        return redirect()->route('admin.employees')->with('success', 'Employé modifié avec succès!');
    }

    // Delete an employee
    public function destroy($id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);
        $employee->delete();
        return redirect()->route('admin.employees')->with('success', 'Employé supprimé avec succès!');
    }
} 