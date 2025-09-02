@extends('layouts.app')

@section('content')
<div style="display:flex;min-height:100vh;background:#fff;">
    @include('partials.sidebar_admin')
    <div style="flex:1;display:flex;align-items:center;justify-content:center;">
        <div style="width:100%;max-width:480px;background:#fff;padding:2.5rem 2rem 2rem 2rem;border-radius:18px;box-shadow:0 8px 32px rgba(91,48,126,0.10);">
            <div style="display:flex;align-items:center;gap:1rem;margin-bottom:2rem;">
                <span style="display:flex;align-items:center;">
                    <svg width="32" height="32" fill="none" stroke="#5B307E" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                </span>
                <h1 style="margin:0;font-size:1.6rem;color:#5B307E;font-weight:700;">Ajouter un SLA</h1>
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
            <form action="{{ route('admin.store.sla') }}" method="POST">
                @csrf
                <div class="form-group" style="margin-bottom:1.2rem;">
                    <label for="name" style="font-weight:600;color:#5B307E;display:block;margin-bottom:0.4rem;font-size:1rem;">Nom du SLA *</label>
                    <input type="text" name="name" id="name" class="form-control" required value="{{ old('name') }}" style="border-radius:8px;padding:0.8rem 1rem;font-size:1rem;width:100%;">
                </div>
                <div class="form-group" style="margin-bottom:1.2rem;">
                    <label for="description" style="font-weight:600;color:#5B307E;display:block;margin-bottom:0.4rem;font-size:1rem;">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="3" style="border-radius:8px;padding:0.8rem 1rem;font-size:1rem;width:100%;resize:vertical;">{{ old('description') }}</textarea>
                </div>
                <div class="form-group" style="margin-bottom:1.2rem;">
                    <label for="duration" style="font-weight:600;color:#5B307E;display:block;margin-bottom:0.4rem;font-size:1rem;">Durée (heures) *</label>
                    <input type="number" name="duration" id="duration" class="form-control" required min="1" value="{{ old('duration') }}" style="border-radius:8px;padding:0.8rem 1rem;font-size:1rem;width:100%;">
                </div>
                <button type="submit" class="btn btn-primary" style="border-radius:999px;font-weight:600;padding:0.9em 2em;width:100%;font-size:1.08rem;margin-top:1.5rem;">Ajouter</button>
            </form>
        </div>
    </div>
</div>
@endsection 