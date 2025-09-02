<aside class="sidebar" id="sidebar">
    <div class="logo" style="width:100%;display:flex;justify-content:center;align-items:center;">
        <div style="background:#fff;border-radius:18px;padding:0.7rem 1.2rem;box-shadow:0 2px 8px rgba(91,48,126,0.10);display:flex;align-items:center;justify-content:center;width:100%;">
            <a href="{{ route('admin.dashboard') }}" style="display:flex;align-items:center;justify-content:center;width:100%;">
                <img src="{{ asset('images/DXC-Logo.png') }}" alt="DXC Logo" style="max-width:90px;display:block;margin:auto;">
            </a>
            <span class="sidebar-role" style="margin-left:12px;font-weight:bold;color:#5B307E;font-size:1.2rem;">Admin</span>
        </div>
    </div>
    <div class="nav-links">
        <a href="{{ route('admin.dashboard') }}" class="btn sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="sidebar-icon" style="display:flex;align-items:center;justify-content:center;height:22px;width:22px;">
                <svg width="22" height="22" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 9h6v6H9z"/></svg>
            </span>
            <span class="sidebar-text">Tableau de bord</span>
        </a>
        <a href="{{ route('admin.incidents') }}" class="btn sidebar-link {{ request()->routeIs('admin.incidents') ? 'active' : '' }}">
            <span class="sidebar-icon" style="display:flex;align-items:center;justify-content:center;height:22px;width:22px;">
                <svg width="22" height="22" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            </span>
            <span class="sidebar-text">Incidents</span>
        </a>
        <a href="{{ route('admin.profile') }}" class="btn sidebar-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
            <span class="sidebar-icon" style="display:flex;align-items:center;justify-content:center;height:22px;width:22px;">
                <svg width="22" height="22" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20v-1a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4v1"/></svg>
            </span>
            <span class="sidebar-text">Mon Profil</span>
        </a>
        <div style="margin-top:2.5rem;">
            <div style="font-weight:700;color:#5B307E;font-size:1.1rem;margin-bottom:0.7em;">Ajouter</div>
            <a href="{{ route('admin.add.project') }}" class="btn sidebar-link">
                <span class="sidebar-icon" style="display:flex;align-items:center;justify-content:center;height:22px;width:22px;">
                    <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 7a2 2 0 0 1 2-2h4l2 3h8a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7z"/></svg>
                </span>
                <span class="sidebar-text">Projet</span>
            </a>
            <a href="{{ route('admin.add.client') }}" class="btn sidebar-link">
                <span class="sidebar-icon" style="display:flex;align-items:center;justify-content:center;height:22px;width:22px;">
                    <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20v-1a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4v1"/></svg>
                </span>
                <span class="sidebar-text">Client</span>
            </a>
            <a href="{{ route('admin.add.employee') }}" class="btn sidebar-link">
                <span class="sidebar-icon" style="display:flex;align-items:center;justify-content:center;height:22px;width:22px;">
                    <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20v-1a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4v1"/><path d="M12 12v4l2 2l-2-2l-2 2l2-2v-4z"/></svg>
                </span>
                <span class="sidebar-text">Employé</span>
            </a>
            <a href="{{ route('admin.add.team') }}" class="btn sidebar-link">
                <span class="sidebar-icon" style="display:flex;align-items:center;justify-content:center;height:22px;width:22px;">
                    <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="7" cy="8" r="3"/><circle cx="17" cy="8" r="3"/><path d="M2 20v-2a4 4 0 0 1 4-4h2a4 4 0 0 1 4 4v2"/><path d="M14 20v-2a4 4 0 0 1 4-4h2a4 4 0 0 1 4 4v2"/></svg>
                </span>
                <span class="sidebar-text">Équipe</span>
            </a>
            <a href="{{ route('admin.admins') }}" class="btn sidebar-link">
                <span class="sidebar-icon" style="display:flex;align-items:center;justify-content:center;height:22px;width:22px;">
                    <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M2 19h20M2 8l5 5 5-9 5 9 5-5"/></svg>
                </span>
                <span class="sidebar-text">Admin</span>
            </a>
        </div>
        <a href="{{ route('logout') }}" class="btn sidebar-link text-danger" style="margin-top:auto;margin-bottom:1rem;">
            <span class="sidebar-icon" style="display:flex;align-items:center;justify-content:center;height:22px;width:22px;">
                <svg width="22" height="22" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M15 12H3"/><path d="M8 16l-5-4 5-4"/><path d="M21 19V5a2 2 0 0 0-2-2h-7"/></svg>
            </span>
            <span class="sidebar-text">Déconnexion</span>
        </a>
    </div>
</aside> 