<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Employee Dashboard') - Incident Management System</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    <button id="sidebar-toggle" aria-label="Toggle sidebar" style="background:none;border:none;position:fixed;top:1rem;left:1rem;z-index:3000;cursor:pointer;display:none;box-shadow:0 2px 8px rgba(91,48,126,0.10);border-radius:8px;padding:0.3rem 0.4rem;">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#5B307E" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
    </button>
    @php
        $user = Auth::user();
    @endphp
    @if ($user && $user->isAdmin())
        @include('partials.sidebar_admin')
    @elseif ($user && $user->isEmployee())
        @include('partials.sidebar_employee')
    @else
        @include('partials.sidebar_client')
    @endif
    <main class="main-content">
        @yield('notification-bell')
        @yield('content')
    </main>
    <div class="sidebar-overlay" id="sidebar-overlay" style="display:none;"></div>
    <script src="{{ asset('js/dashboard.js') }}"></script>
</body>
</html> 