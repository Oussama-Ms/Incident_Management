@extends('layouts.app')

@section('content')
<div class="d-flex">
    @include('partials.sidebar_admin')
    <div class="flex-grow-1 p-4" style="background:#fff;min-height:100vh;">
        <div class="dashboard-header">
            <h1 style="margin:0;font-size:2rem;color:#5B307E;font-weight:700;">Gestion des Incidents</h1>
        </div>
        <!-- Minimal, Modern Search and Filters Bar -->
        <form id="incident-filter-form" method="GET" action="" class="incident-filter-bar" style="background:#fff;border-radius:14px;box-shadow:0 2px 8px rgba(91,48,126,0.08);padding:1.2rem 2rem;margin-bottom:2.5rem;display:flex;flex-wrap:wrap;gap:1.2rem;align-items:center;width:100%;max-width:none;">
            <div style="position:relative;flex:2;min-width:220px;">
                <input type="text" name="search" id="search-input" value="{{ request('search') }}" placeholder="Rechercher un incident..." class="form-control" style="width:100%;padding-left:2.2em;background:#f7f7fa;border:1.5px solid #e2e8f0;border-radius:999px;">
                <span style="position:absolute;left:0.8em;top:50%;transform:translateY(-50%);color:#aaa;">
                    <svg width="18" height="18" fill="none" stroke="#aaa" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </span>
            </div>
            <select name="status" id="status-select" class="form-select" style="border-radius:999px;padding:0.5em 1.5em;min-width:120px;background:#f7f7fa;border:1.5px solid #e2e8f0;flex:1;">
                <option value="">Statut</option>
                <option value="Nouveau" {{ request('status')=='Nouveau'?'selected':'' }}>Nouveau</option>
                <option value="En cours" {{ request('status')=='En cours'?'selected':'' }}>En cours</option>
                <option value="Résolu" {{ request('status')=='Résolu'?'selected':'' }}>Résolu</option>
                <option value="Fermé" {{ request('status')=='Fermé'?'selected':'' }}>Fermé</option>
            </select>
            <select name="team" id="team-select" class="form-select" style="border-radius:999px;padding:0.5em 1.5em;min-width:120px;background:#f7f7fa;border:1.5px solid #e2e8f0;flex:1;">
                <option value="">Équipe</option>
                @foreach($teams as $team)
                    <option value="{{ $team->id }}" {{ request('team')==$team->id?'selected':'' }}>{{ $team->name }}</option>
                @endforeach
            </select>
            <input type="date" name="date" id="date-input" value="{{ request('date') }}" class="form-control" style="border-radius:999px;padding:0.5em 1.5em;min-width:120px;background:#f7f7fa;border:1.5px solid #e2e8f0;flex:1;">
            <!-- Removed filter button -->
        </form>
        <div class="incident-grid">
            @php
                $filteredIncidents = $incidents;
                if(request('search')) {
                    $filteredIncidents = $filteredIncidents->filter(function($i) {
                        return stristr($i->title, request('search')) || stristr($i->user->name ?? '', request('search'));
                    });
                }
                if(request('status')) {
                    $filteredIncidents = $filteredIncidents->where('status', request('status'));
                }
                if(request('team')) {
                    $filteredIncidents = $filteredIncidents->filter(function($i) {
                        return $i->projet && $i->projet->team_id == request('team');
                    });
                }
                if(request('date')) {
                    $filteredIncidents = $filteredIncidents->filter(function($i) {
                        return $i->creationdate && $i->creationdate->format('Y-m-d') == request('date');
                    });
                }
            @endphp
            @forelse($filteredIncidents as $incident)
            @php
                $statusColors = [
                    'Nouveau' => ['bg' => '#1976d2', 'text' => '#fff'],
                    'En cours' => ['bg' => '#ffc107', 'text' => '#333'],
                    'Résolu' => ['bg' => '#28a745', 'text' => '#fff'],
                    'Fermé' => ['bg' => '#6c757d', 'text' => '#fff'],
                ];
                $statusConfig = $statusColors[$incident->status ?? 'Nouveau'] ?? $statusColors['Nouveau'];
                $badgeBg = $statusConfig['bg'];
                $badgeColor = $statusConfig['text'];
            @endphp
            <div class="incident-card" style="background:#fff;border-radius:18px;box-shadow:0 8px 32px rgba(91,48,126,0.12);border:1.5px solid #e2e8f0;padding:0;display:flex;flex-direction:column;position:relative;transition:box-shadow 0.2s;min-height:180px;width:100%;overflow:hidden;">
                <!-- Status Bar with text and color only at the very top -->
                <div style="background:{{ $badgeBg }};color:{{ $badgeColor }};padding:0.8em 1.2em;font-weight:600;font-size:1.08rem;letter-spacing:0.5px;min-height:2.2em;display:flex;align-items:center;justify-content:center;z-index:2;">
                    <span>@if($incident->status === 'Nouveau') 🆕 @elseif($incident->status === 'En cours') ⏳ @elseif($incident->status === 'Résolu') ✅ @elseif($incident->status === 'Fermé') 🔒 @endif {{ $incident->status ?? 'Ouvert' }}</span>
                </div>
                <div class="incident-content" style="padding:1.5rem;flex:1;display:flex;flex-direction:column;justify-content:space-between;height:100%;">
                    <h3 class="incident-title" style="margin:0 0 1em 0;font-size:1.35rem;color:#5B307E;font-weight:700;line-height:1.3;">{{ $incident->title }}</h3>
                    <div class="incident-meta" style="display:flex;flex-wrap:wrap;gap:2em 2.5em;margin-bottom:1.2em;align-items:center;">
                        <div style="font-size:1.05rem;color:#764ba2;"><span style="font-weight:600;">Client:</span> {{ $incident->user ? $incident->user->name : 'N/A' }}</div>
                        <div style="font-size:1.05rem;color:#1976d2;"><span style="font-weight:600;">Priorité:</span> {{ $incident->priority ?? 'Normale' }}</div>
                        <div style="font-size:1.05rem;color:#ad1457;"><span style="font-weight:600;">Catégorie:</span> {{ $incident->category ?? 'Non définie' }}</div>
                        <div style="font-size:1.05rem;color:#764ba2;"><span style="font-weight:600;">Équipe:</span> @if($incident->projet && $incident->projet->team){{ $incident->projet->team->name }}@else<span style="color:#888;">Non assignée</span>@endif</div>
                        <div style="font-size:1.05rem;color:#888;"><span style="font-weight:600;">Date:</span> {{ $incident->creationdate ? $incident->creationdate->format('d/m/Y') : '' }}</div>
                    </div>
                    <div style="margin-top:auto;">
                        <div style="display:flex;align-items:center;gap:1em;flex-wrap:wrap;margin-bottom:1em;">
                            <form method="POST" action="{{ route('admin.incidents.assignTeam', $incident->id) }}" style="display:flex;align-items:center;gap:0.7em;flex-wrap:wrap;flex:1;">
                                @csrf
                                <select id="team_id_{{ $incident->id }}" name="team_id" class="form-select form-select-sm" style="min-width:140px;border-radius:999px;padding:0.5em 1.5em;background:#f7f7fa;border:1.5px solid #e2e8f0;">
                                    <option value="">Assigner une équipe</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}" {{ ($incident->projet && $incident->projet->team_id == $team->id) ? 'selected' : '' }}>{{ $team->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-outline-primary btn-sm" style="border-radius:999px;padding:0.5em 1.5em;font-weight:600;">Assigner</button>
                            </form>
                        </div>
                        <a href="{{ route('admin.incidents.show', $incident->id) }}" class="incident-action-btn" style="background:#5B307E;color:#fff;padding:0.8em 2em;border-radius:999px;font-weight:600;text-decoration:none;display:inline-block;font-size:1.08rem;text-align:center;">Voir détails</a>
                    </div>
                </div>
            </div>
            @empty
            <div style="grid-column:1/-1;text-align:center;color:#888;font-size:1.1rem;">Aucun incident trouvé.</div>
            @endforelse
        </div>
    </div>
</div>
<script>
// Make search and filters reactive
const filterForm = document.getElementById('incident-filter-form');
const searchInput = document.getElementById('search-input');
const statusSelect = document.getElementById('status-select');
const teamSelect = document.getElementById('team-select');
const dateInput = document.getElementById('date-input');

searchInput.addEventListener('input', () => filterForm.submit());
statusSelect.addEventListener('change', () => filterForm.submit());
teamSelect.addEventListener('change', () => filterForm.submit());
dateInput.addEventListener('change', () => filterForm.submit());
</script>
@endsection 