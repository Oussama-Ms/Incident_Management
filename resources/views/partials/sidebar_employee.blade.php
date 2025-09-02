<aside class="sidebar" style="display:flex;flex-direction:column;height:100vh;">
    <div class="logo" style="width:100%;display:flex;justify-content:center;align-items:center;">
        <div style="background:#fff;border-radius:18px;padding:0.7rem 1.2rem;box-shadow:0 2px 8px rgba(91,48,126,0.10);display:flex;align-items:center;justify-content:center;width:100%;">
            <a href="{{ route('employee.incidents.index') }}" style="display:flex;align-items:center;justify-content:center;width:100%;">
                <img src="{{ asset('images/DXC-Logo.png') }}" alt="DXC Logo" style="max-width:90px;display:block;margin:auto;">
            </a>
        </div>
    </div>
    <div class="nav-links" style="flex:1 1 auto;display:flex;flex-direction:column;">
        <a href="{{ route('employee.incidents.index') }}" class="btn sidebar-link">
            <span class="sidebar-icon" style="display:flex;align-items:center;justify-content:center;height:22px;width:22px;">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            </span>
            <span class="sidebar-text">Tous les Incidents</span>
        </a>
        @php
            $user = Auth::user();
        @endphp
        <a href="{{ route('profile.show') }}" class="btn sidebar-link">
            <span class="sidebar-icon" style="display:flex;align-items:center;justify-content:center;height:22px;width:22px;">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20v-1a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4v1"/></svg>
            </span>
            <span class="sidebar-text">Mon Profil</span>
        </a>
        <a href="{{ route('logout') }}" class="btn sidebar-link" style="margin-top:auto;">
            <span class="sidebar-icon" style="display:flex;align-items:center;justify-content:center;height:22px;width:22px;">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M15 12H3"/><path d="M8 16l-5-4 5-4"/><path d="M21 19V5a2 2 0 0 0-2-2h-7"/></svg>
            </span>
            <span class="sidebar-text">Déconnexion</span>
        </a>
    </div>
</aside> 