<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Incident;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $incidentCount = Incident::where('user_id', $user->id)->count();
        $lastIncident = Incident::where('user_id', $user->id)->orderByDesc('creationdate')->first();
        return view('dashboards.profile', compact('user', 'incidentCount', 'lastIncident'));
    }
}
