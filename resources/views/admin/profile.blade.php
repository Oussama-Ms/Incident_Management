@extends('layouts.app')

@section('title', 'Mon Profil')

@section('content')
<div class="dashboard-header">
    <h1>Mon Profil</h1>
</div>
<div>
    <!-- User Info Card -->
    <div class="card" style="margin-bottom:2rem;text-align:center;background:linear-gradient(135deg, #667eea 0%, #764ba2 100%);color:#fff;border-radius:16px;overflow:hidden;">
        <div style="padding:3rem 2rem;">
            <div style="width:100px;height:100px;background:rgba(255,255,255,0.2);border-radius:50%;margin:0 auto 1.5rem;display:flex;align-items:center;justify-content:center;">
                <svg width="50" height="50" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="8" r="4"/>
                    <path d="M4 20v-1a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4v1"/>
                </svg>
            </div>
            <h2 style="margin:0 0 0.5rem;font-size:1.8rem;font-weight:700;">{{ $user->name }}</h2>
            <p style="margin:0;opacity:0.9;font-size:1.1rem;">{{ $user->email }}</p>
        </div>
    </div>
    <!-- Stats Grid -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(250px, 1fr));gap:1.5rem;margin-bottom:2rem;">
        <!-- Role Card -->
        <div class="card" style="padding:1.5rem;border-radius:12px;border-left:5px solid var(--primary);">
            <div style="display:flex;align-items:center;margin-bottom:1rem;">
                <div style="width:40px;height:40px;background:var(--primary);border-radius:8px;display:flex;align-items:center;justify-content:center;margin-right:1rem;">
                    <svg width="22" height="22" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M2 19h20M2 8l5 5 5-9 5 9 5-5"/></svg>
                </div>
                <div>
                    <h3 style="margin:0;font-size:1.1rem;color:var(--primary);">Rôle</h3>
                    <p style="margin:0;font-weight:600;font-size:1.2rem;">Administrateur</p>
                </div>
            </div>
        </div>
        <!-- Incidents Card -->
        <div class="card" style="padding:1.5rem;border-radius:12px;border-left:5px solid #28a745;">
            <div style="display:flex;align-items:center;margin-bottom:1rem;">
                <div style="width:40px;height:40px;background:#28a745;border-radius:8px;display:flex;align-items:center;justify-content:center;margin-right:1rem;">
                    <span style="color:#fff;font-size:1.2rem;">📊</span>
                </div>
                <div>
                    <h3 style="margin:0;font-size:1.1rem;color:#28a745;">Incidents gérés</h3>
                    <p style="margin:0;font-weight:600;font-size:1.2rem;">{{ $incidentCount }}</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Additional Info -->
    <div class="card" style="padding:2rem;border-radius:12px;background:#fff3cd;border-left:5px solid #ffc107;display:flex;align-items:center;gap:1rem;">
        <span style="display:flex;align-items:center;">
            <svg width="22" height="22" fill="none" stroke="#ffc107" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M2 19h20M2 8l5 5 5-9 5 9 5-5"/></svg>
        </span>
        <div>
            <h3 style="margin:0 0 1rem;color:#856404;display:inline;vertical-align:middle;">Administrateur</h3>
            <p style="margin:0;color:#856404;line-height:1.6;">Vous avez un accès complet à la gestion des incidents et des utilisateurs. Vous pouvez superviser toutes les activités du système.</p>
        </div>
    </div>
</div>
@endsection 