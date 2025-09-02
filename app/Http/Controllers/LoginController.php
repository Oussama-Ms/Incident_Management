<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|exists:user,email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'employee') {
                $employee = $user->employee;
                if (!$employee) {
                    Auth::logout();
                    return back()->withErrors(['email' => 'Employee account not found.']);
                }
            }

            switch ($user->role) {
                case 'client':
                    return redirect()->route('incidents.index');
                case 'employee':
                    return redirect()->route('employee.incidents.index');
                case 'admin':
                    return redirect()->route('admin.dashboard');
                default:
                    Auth::logout();
                    return back()->withErrors(['email' => 'Invalid role assigned.']);
            }
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }
}