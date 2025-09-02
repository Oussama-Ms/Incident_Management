@extends('layouts.app')

@section('notification-bell')
    @include('partials.notification_bell')
@endsection

@section('content')
<div class="dashboard-header client-incidents-title-up">
    <h1 style="margin:0;font-size:2rem;color:#5B307E;font-weight:700;">Mes Incidents</h1>
</div>
<p style="color:#666;margin-top:0;margin-bottom:1.5rem;">Suivez l'état de vos demandes d'assistance</p>

@if (session('success'))
    <div class="alert alert-success" style="background:#d4edda;color:#155724;padding:1rem;border-radius:8px;margin-bottom:1.5rem;border-left:4px solid #28a745;">{{ session('success') }}</div>
@endif

<!-- Search/Filter Bar -->
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
    <input type="date" name="date" id="date-input" value="{{ request('date') }}" class="form-control" style="border-radius:999px;padding:0.5em 1.5em;min-width:120px;background:#f7f7fa;border:1.5px solid #e2e8f0;flex:1;">
</form>
<script>
const filterForm = document.getElementById('incident-filter-form');
const searchInput = document.getElementById('search-input');
const statusSelect = document.getElementById('status-select');
const dateInput = document.getElementById('date-input');
searchInput.addEventListener('input', () => filterForm.submit());
statusSelect.addEventListener('change', () => filterForm.submit());
dateInput.addEventListener('change', () => filterForm.submit());
</script>

<div>
    @if ($incidents->isEmpty())
        <div class="card" style="text-align:center;padding:3rem;color:#666;">
            <h3 style="margin:0 0 0.5rem;color:#333;">Aucun incident signalé</h3>
            <p style="margin:0;">Vous n'avez pas encore signalé d'incident. Commencez par créer votre première demande d'assistance.</p>
            <a href="{{ route('incidents.create') }}" class="btn" style="margin-top:1rem;display:inline-block;background:var(--primary);color:#fff;padding:0.8rem 1.5rem;border-radius:8px;text-decoration:none;font-weight:600;">
                Créer un incident
            </a>
        </div>
    @else
        <div class="incident-grid" style="display:grid;grid-template-columns:repeat(1,1fr);gap:1.2rem;width:100%;padding:0;">
            @foreach ($incidents as $incident)
                @php
                    $statusColors = [
                        'Nouveau' => '#1976d2',
                        'En cours' => '#ffc107',
                        'Résolu' => '#28a745',
                        'Fermé' => '#6c757d',
                    ];
                    $borderColor = $statusColors[$incident->status ?? 'Nouveau'] ?? '#1976d2';
                @endphp
                <div class="incident-card" style="background:#fff;border-radius:18px;box-shadow:0 8px 32px rgba(91,48,126,0.12);border:1.5px solid #e2e8f0;padding:2.2rem 1.2rem 2rem 1.2rem;display:flex;flex-direction:column;gap:1.2rem;position:relative;transition:box-shadow 0.2s;min-height:180px;width:100%;border-top:none;">
                    <!-- Status Bar with text and color only at the very top -->
                    <div style="position:absolute;top:0;left:0;width:100%;background:{{ $borderColor }};color:#fff;padding:0.5em 0;font-weight:600;font-size:1.08rem;letter-spacing:0.5px;min-height:2.2em;display:flex;align-items:center;justify-content:center;z-index:2;border-radius:18px 18px 0 0;">
                        <span>{{ $incident->status }}</span>
                    </div>
                    <div class="incident-content" style="padding:2.2em 0 0.5em 0;flex:1;display:flex;flex-direction:column;justify-content:space-between;height:100%;">
                        <h3 class="incident-title" style="margin:0 0 1em 0;font-size:1.35rem;color:#5B307E;font-weight:700;line-height:1.3;">{{ $incident->title }}</h3>
                        <div class="incident-meta" style="display:flex;flex-wrap:wrap;gap:2em 2.5em;margin-bottom:1.2em;align-items:center;">
                            <div style="font-size:1.05rem;color:#764ba2;"><span style="font-weight:600;">Projet:</span> {{ $incident->projet->nom ?? 'Projet non défini' }}</div>
                            <div style="font-size:1.05rem;color:#1976d2;"><span style="font-weight:600;">Priorité:</span> {{ $incident->priority ?? 'Normale' }}</div>
                            <div style="font-size:1.05rem;color:#888;"><span style="font-weight:600;">Date:</span> {{ $incident->creationdate->format('d/m/Y') }}</div>
                        </div>
                        <div style="margin-top:auto;">
                            <a href="{{ route('incidents.show', $incident->id) }}" class="incident-action-btn" style="background:#5B307E;color:#fff;padding:0.8em 2em;border-radius:999px;font-weight:600;text-decoration:none;display:inline-block;font-size:1.08rem;">Voir détails</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- Quick Action -->
        <div style="text-align:center;margin-top:2rem;">
            <a href="{{ route('incidents.create') }}" class="btn" style="background:var(--primary);color:#fff;padding:1rem 2rem;border-radius:8px;text-decoration:none;font-weight:600;font-size:1.1rem;box-shadow:0 4px 12px rgba(91,48,126,0.2);">
                Créer un nouvel incident
            </a>
        </div>
    @endif
</div>
@endsection 