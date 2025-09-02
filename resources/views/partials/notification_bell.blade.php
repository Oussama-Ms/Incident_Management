@php
    $unreadNotifications = \App\Models\Notification::where('user_id', Auth::id())->where('is_read', false)->orderByDesc('created_at')->take(5)->get();
    $userRole = Auth::user()->role ?? null;
@endphp
<div style="position:sticky;top:0;right:0;z-index:2000;text-align:right;margin-bottom:1.5rem;">
    <div style="display:inline-block; position:relative;">
        <button id="notif-bell" onclick="toggleNotifDropdown(event)" style="background:none;border:none;cursor:pointer;position:relative;padding:0;outline:none;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
            <span id="notification-count" style="position:absolute;top:-2px;right:-2px;background:var(--primary);color:#fff;border-radius:50%;font-size:0.8rem;padding:0 6px;min-width:18px;text-align:center;display:{{ $unreadNotifications->count() ? 'block' : 'none' }};">{{ $unreadNotifications->count() }}</span>
        </button>
        <div id="notif-dropdown" style="display:none;position:absolute;right:0;top:2.5rem;background:#fff;color:var(--text);min-width:270px;box-shadow:0 4px 16px rgba(91,48,126,0.10);border-radius:10px;z-index:1000;overflow:hidden;">
            <div style="font-weight:600;padding:0.8rem 1rem;background:var(--primary);color:#fff;">Notifications</div>
            @if($unreadNotifications->isEmpty())
                <div style="padding:1rem;">Aucune nouvelle notification</div>
            @else
                @foreach($unreadNotifications as $notif)
                    @php
                        $conversationUrl = $notif->incident_id
                            ? ($userRole === 'employee'
                                ? route('employee.incidents.show', $notif->incident_id)
                                : route('incidents.show', $notif->incident_id))
                            : '#';
                    @endphp
                    <div style="padding:0.7rem 1rem;border-bottom:1px solid #eee;cursor:pointer;" onclick="window.location.href='{{ $conversationUrl }}'">
                        @if($notif->sender)
                            <span style="font-weight:600;color:#5B307E;">{{ $notif->sender->name }}</span><br>
                        @endif
                        @if($notif->incident)
                            <span style="font-size:0.95em;color:#888;">Incident: {{ $notif->incident->title }}</span><br>
                        @endif
                        <span style="font-weight:500;">{{ $notif->message }}</span><br>
                        <small style="color:#888;">{{ $notif->created_at }}</small>
                        @if($notif->incident_id)
                            <br><span style="color:var(--primary);font-weight:600;">Voir la conversation</span>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
<script>
    function toggleNotifDropdown(event) {
        event.stopPropagation();
        var dropdown = document.getElementById('notif-dropdown');
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        
        if (dropdown.style.display === 'block') {
            loadNotifications();
        }
    }
    
    document.addEventListener('click', function(event) {
        var dropdown = document.getElementById('notif-dropdown');
        var bell = document.getElementById('notif-bell');
        if (dropdown && dropdown.style.display === 'block' && !bell.contains(event.target)) {
            dropdown.style.display = 'none';
        }
    });
    
    // Real-time notification updates
    function updateNotificationCount() {
        fetch('{{ route("notifications.count") }}')
            .then(response => response.json())
            .then(data => {
                const countElement = document.getElementById('notification-count');
                if (data.count > 0) {
                    countElement.textContent = data.count;
                    countElement.style.display = 'block';
                } else {
                    countElement.style.display = 'none';
                }
            })
            .catch(error => console.error('Error updating notification count:', error));
    }
    
    function loadNotifications() {
        fetch('{{ route("notifications.get") }}')
            .then(response => response.json())
            .then(data => {
                const dropdown = document.getElementById('notif-dropdown');
                let html = '<div style="font-weight:600;padding:0.8rem 1rem;background:var(--primary);color:#fff;">Notifications</div>';
                
                if (data.notifications.length === 0) {
                    html += '<div style="padding:1rem;">Aucune nouvelle notification</div>';
                } else {
                    data.notifications.forEach(notif => {
                        const conversationUrl = notif.incident_id 
                            ? ('{{ $userRole }}' === 'employee' 
                                ? '{{ route("employee.incidents.show", ":id") }}'.replace(':id', notif.incident_id)
                                : '{{ route("incidents.show", ":id") }}'.replace(':id', notif.incident_id))
                            : '#';
                        
                        html += `<div style="padding:0.7rem 1rem;border-bottom:1px solid #eee;cursor:pointer;" onclick="window.location.href='${conversationUrl}'">
                            ${notif.sender ? `<span style="font-weight:600;color:#5B307E;">${notif.sender.name}</span><br>` : ''}
                            ${notif.incident ? `<span style="font-size:0.95em;color:#888;">Incident: ${notif.incident.title}</span><br>` : ''}
                            <span style="font-weight:500;">${notif.message}</span><br>
                            <small style="color:#888;">${notif.created_at}</small>
                            ${notif.incident_id ? '<br><span style="color:var(--primary);font-weight:600;">Voir la conversation</span>' : ''}
                        </div>`;
                    });
                }
                
                dropdown.innerHTML = html;
            })
            .catch(error => console.error('Error loading notifications:', error));
    }
    
    // Update notifications every 30 seconds
    setInterval(updateNotificationCount, 30000);
    
    // Initial load
    updateNotificationCount();
</script> 