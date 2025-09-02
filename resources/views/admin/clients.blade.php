@extends('layouts.app')

@section('content')
<div style="display:flex;min-height:100vh;background:#fff;">
    @include('partials.sidebar_admin')
    <div style="flex:1;padding:2.5rem 2rem 2rem 2rem;">
        <div class="dashboard-header" style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;">
            <div style="display:flex;align-items:center;gap:1rem;">
                <span style="display:flex;align-items:center;">
                    <svg width="32" height="32" fill="none" stroke="#5B307E" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20v-1a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4v1"/></svg>
                </span>
                <h1 style="margin:0;font-size:2rem;color:#5B307E;font-weight:700;">Clients</h1>
            </div>
            <a href="{{ route('admin.create.client') }}" class="btn btn-primary" style="border-radius:999px;font-weight:600;padding:0.7em 2em;box-shadow:0 4px 16px rgba(91,48,126,0.12);font-size:1.1rem;">+ Ajouter un client</a>
        </div>
        @if(session('success'))
            <div class="alert alert-success" style="background:#d4edda;color:#155724;padding:1rem;border-radius:8px;margin-bottom:1.5rem;border-left:4px solid #28a745;box-shadow:0 2px 8px rgba(40,167,69,0.08);">{{ session('success') }}</div>
        @endif
        <div class="client-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(270px,1fr));gap:1.5rem;">
            @if($clients->isEmpty())
                <div style="color:#888;text-align:center;font-size:1.1rem;grid-column:1/-1;background:#fff;padding:2.5rem 0;border-radius:14px;box-shadow:0 2px 8px rgba(91,48,126,0.08);">Aucun client trouvé.</div>
            @else
                @foreach($clients as $client)
                    <div class="card client-card" style="background:#fff;border-radius:16px;box-shadow:0 8px 32px rgba(91,48,126,0.10);padding:2rem 1.5rem;display:flex;flex-direction:column;gap:0.7rem;position:relative;transition:box-shadow 0.2s;align-items:flex-start;">
                        <div style="display:flex;align-items:center;gap:0.7rem;">
                            <span style="display:flex;align-items:center;">
                                <svg width="24" height="24" fill="none" stroke="#5B307E" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20v-1a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4v1"/></svg>
                            </span>
                            <div style="font-size:1.2rem;font-weight:700;color:#5B307E;">{{ $client->name }}</div>
                        </div>
                        <div style="color:#555;opacity:0.95;font-size:1rem;margin-top:0.5rem;">
                            {{ $client->email }}
                        </div>
                        <div style="display:flex;gap:0.7rem;margin-top:1.2rem;">
                            <a href="{{ route('admin.edit.client', $client->id) }}" class="btn btn-primary" style="border-radius:8px;font-weight:600;padding:0.5em 1.2em;font-size:0.98rem;">Éditer</a>
                            <form action="{{ route('admin.delete.client', $client->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce client ? Cette action est irréversible.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="border-radius:8px;font-weight:600;padding:0.5em 1.2em;font-size:0.98rem;background:#dc3545;color:#fff;">Supprimer</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection 