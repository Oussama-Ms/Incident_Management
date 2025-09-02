@extends('layouts.app')

@section('content')
<div style="display:flex;min-height:100vh;background:#fff;">
    @include('partials.sidebar_admin')
    <div style="flex:1;display:flex;align-items:center;justify-content:center;">
        <div style="width:100%;max-width:480px;background:#fff;padding:2.5rem 2rem 2rem 2rem;border-radius:18px;box-shadow:0 8px 32px rgba(91,48,126,0.10);">
            <div style="display:flex;align-items:center;gap:1rem;margin-bottom:2rem;">
                <span style="display:flex;align-items:center;">
                    <svg width="32" height="32" fill="none" stroke="#5B307E" stroke-width="2" viewBox="0 0 24 24"><path d="M3 7a2 2 0 0 1 2-2h4l2 3h8a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7z"/></svg>
                </span>
                <h1 style="margin:0;font-size:1.6rem;color:#5B307E;font-weight:700;">Ajouter un projet</h1>
            </div>
            @if(session('success'))
                <div class="alert alert-success" style="background:#d4edda;color:#155724;padding:1rem;border-radius:8px;margin-bottom:1.2rem;border-left:4px solid #28a745;box-shadow:0 2px 8px rgba(40,167,69,0.08);">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger" style="background:#f8d7da;color:#721c24;padding:1rem;border-radius:8px;margin-bottom:1.2rem;border-left:4px solid #dc3545;">
                    <ul style="margin:0;padding-left:1.5rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('admin.store.project') }}" method="POST">
                @csrf
                <div class="form-group" style="margin-bottom:1.2rem;">
                    <label for="nom" style="font-weight:600;color:#5B307E;display:block;margin-bottom:0.4rem;font-size:1rem;">Nom du projet *</label>
                    <input type="text" name="nom" id="nom" class="form-control" required value="{{ old('nom') }}" style="border-radius:8px;padding:0.8rem 1rem;font-size:1rem;width:100%;">
                </div>
                <div class="form-group" style="margin-bottom:1.2rem;">
                    <label for="description" style="font-weight:600;color:#5B307E;display:block;margin-bottom:0.4rem;font-size:1rem;">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="4" style="border-radius:8px;padding:0.8rem 1rem;font-size:1rem;width:100%;resize:vertical;">{{ old('description') }}</textarea>
                </div>
                <div class="form-group" style="margin-bottom:1.2rem;">
                    <label for="responseTime" style="font-weight:600;color:#5B307E;display:block;margin-bottom:0.4rem;font-size:1rem;">Délai de réponse (heures) *</label>
                    <input type="number" name="responseTime" id="responseTime" class="form-control" required min="1" value="{{ old('responseTime') }}" style="border-radius:8px;padding:0.8rem 1rem;font-size:1rem;width:100%;">
                </div>
                <div class="form-group" style="margin-bottom:1.2rem;">
                    <label for="resolutionTime" style="font-weight:600;color:#5B307E;display:block;margin-bottom:0.4rem;font-size:1rem;">Délai de résolution (heures) *</label>
                    <input type="number" name="resolutionTime" id="resolutionTime" class="form-control" required min="1" value="{{ old('resolutionTime') }}" style="border-radius:8px;padding:0.8rem 1rem;font-size:1rem;width:100%;">
                </div>
                <div class="form-group" style="margin-bottom:1.2rem;">
                    <label for="priority" style="font-weight:600;color:#5B307E;display:block;margin-bottom:0.4rem;font-size:1rem;">Priorité SLA *</label>
                    <select name="priority" id="priority" class="form-control" required style="border-radius:8px;padding:0.8rem 1rem;font-size:1rem;width:100%;">
                        <option value="">-- Sélectionner la priorité --</option>
                        <option value="Low" {{ old('priority') == 'Low' ? 'selected' : '' }}>Faible</option>
                        <option value="Normal" {{ old('priority') == 'Normal' ? 'selected' : '' }}>Normale</option>
                        <option value="High" {{ old('priority') == 'High' ? 'selected' : '' }}>Haute</option>
                        <option value="Critical" {{ old('priority') == 'Critical' ? 'selected' : '' }}>Critique</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:1.2rem;">
                    <label for="startDate" style="font-weight:600;color:#5B307E;display:block;margin-bottom:0.4rem;font-size:1rem;">Date de début *</label>
                    <input type="date" name="startDate" id="startDate" class="form-control" required value="{{ old('startDate') }}" style="border-radius:8px;padding:0.8rem 1rem;font-size:1rem;width:100%;">
                </div>
                <div class="form-group" style="margin-bottom:1.2rem;">
                    <label for="endDate" style="font-weight:600;color:#5B307E;display:block;margin-bottom:0.4rem;font-size:1rem;">Date de fin *</label>
                    <input type="date" name="endDate" id="endDate" class="form-control" required value="{{ old('endDate') }}" style="border-radius:8px;padding:0.8rem 1rem;font-size:1rem;width:100%;">
                </div>
                <div class="form-group" style="margin-bottom:1.2rem;">
                    <label for="status" style="font-weight:600;color:#5B307E;display:block;margin-bottom:0.4rem;font-size:1rem;">Statut *</label>
                    <select name="status" id="status" class="form-control" required style="border-radius:8px;padding:0.8rem 1rem;font-size:1rem;width:100%;">
                        <option value="">-- Sélectionner le statut --</option>
                        <option value="Nouveau" {{ old('status') == 'Nouveau' ? 'selected' : '' }}>Nouveau</option>
                        <option value="En cours" {{ old('status') == 'En cours' ? 'selected' : '' }}>En cours</option>
                        <option value="Terminé" {{ old('status') == 'Terminé' ? 'selected' : '' }}>Terminé</option>
                        <option value="Annulé" {{ old('status') == 'Annulé' ? 'selected' : '' }}>Annulé</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:1.2rem;">
                    <label for="Client_id" style="font-weight:600;color:#5B307E;display:block;margin-bottom:0.4rem;font-size:1rem;">Client *</label>
                    <select name="Client_id" id="Client_id" class="form-control" required style="border-radius:8px;padding:0.8rem 1rem;font-size:1rem;width:100%;">
                        <option value="">-- Sélectionner un client --</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('Client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }} ({{ $client->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:1.2rem;">
                    <label for="team_id" style="font-weight:600;color:#5B307E;display:block;margin-bottom:0.4rem;font-size:1rem;">Équipe *</label>
                    <select name="team_id" id="team_id" class="form-control" required style="border-radius:8px;padding:0.8rem 1rem;font-size:1rem;width:100%;">
                        <option value="">-- Sélectionner une équipe --</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" {{ old('team_id') == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="border-radius:999px;font-weight:600;padding:0.9em 2em;width:100%;font-size:1.08rem;margin-top:1.5rem;">Ajouter</button>
            </form>
        </div>
    </div>
</div>
@endsection 