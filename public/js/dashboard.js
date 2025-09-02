// Notification dropdown toggle
window.onclick = function(event) {
    if (!event.target.matches('button') && !event.target.closest('#notif-dropdown')) {
        var dropdown = document.getElementById('notif-dropdown');
        if (dropdown && dropdown.classList.contains('show')) {
            dropdown.classList.remove('show');
        }
    }
}

// Sidebar collapse/expand and overlay for mobile
const sidebar = document.getElementById('sidebar');
const sidebarToggle = document.getElementById('sidebar-toggle');
const mainContent = document.querySelector('.main-content');
const sidebarOverlay = document.getElementById('sidebar-overlay');

function isMobile() {
    return window.innerWidth <= 600;
}

function openSidebar() {
    if (sidebar) {
        sidebar.classList.add('open');
        if (sidebarOverlay) sidebarOverlay.style.display = 'block';
    }
}
function closeSidebar() {
    if (sidebar) {
        sidebar.classList.remove('open');
        if (sidebarOverlay) sidebarOverlay.style.display = 'none';
    }
}
if (sidebar && sidebarToggle) {
    sidebarToggle.style.display = 'block';
    sidebarToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        if (isMobile()) {
            if (sidebar.classList.contains('open')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        } else {
            sidebar.classList.toggle('collapsed');
        }
    });
}
if (sidebarOverlay) {
    sidebarOverlay.addEventListener('click', function() {
        closeSidebar();
    });
}
window.addEventListener('resize', function() {
    if (!isMobile()) {
        closeSidebar();
        sidebar.classList.remove('collapsed');
    }
}); 