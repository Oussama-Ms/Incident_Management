@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="d-flex">
    @include('partials.sidebar_admin')
    <div class="flex-grow-1 p-4" style="background:#fff;min-height:100vh;">
        <div class="dashboard-header">
            <h1 style="margin:0;font-size:2rem;color:#5B307E;font-weight:700;">Tableau de bord</h1>
        </div>
        <!-- Creative Stats Section -->
        <div class="dashboard-stats-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:1.2rem 1.5rem;margin-bottom:2rem;">
            <a href="{{ route('admin.clients') }}" class="stat-card-link">
                <div class="card stat-card stat-card-hover" style="background:#fff;color:#5B307E;border:2px solid #5B307E;">
                    <div class="stat-label" style="color:#5B307E;">Clients</div>
                    <div class="stat-value" style="font-size:2rem;color:#5B307E;">{{ $clientCount }}</div>
                    <div style="font-size:1.5rem;">
                        <svg width="28" height="28" fill="none" stroke="#5B307E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20v-1a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4v1"/></svg>
                    </div>
                </div>
            </a>
            <a href="{{ route('admin.employees') }}" class="stat-card-link">
                <div class="card stat-card stat-card-hover" style="background:#fff;color:#5B307E;border:2px solid #5B307E;">
                    <div class="stat-label" style="color:#5B307E;">Employés</div>
                    <div class="stat-value" style="font-size:2rem;color:#5B307E;">{{ $employeeCount }}</div>
                    <div style="font-size:1.5rem;">
                        <svg width="28" height="28" fill="none" stroke="#5B307E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20v-1a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4v1"/><path d="M12 12v4l2 2l-2-2l-2 2l2-2v-4z"/></svg>
                    </div>
                </div>
            </a>
            <a href="{{ route('admin.teams') }}" class="stat-card-link">
                <div class="card stat-card stat-card-hover" style="background:#fff;color:#5B307E;border:2px solid #5B307E;">
                    <div class="stat-label" style="color:#5B307E;">Équipes</div>
                    <div class="stat-value" style="font-size:2rem;color:#5B307E;">{{ $teamCount }}</div>
                    <div style="font-size:1.5rem;">
                        <svg width="28" height="28" fill="none" stroke="#5B307E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="7" cy="8" r="3"/><circle cx="17" cy="8" r="3"/><path d="M2 20v-2a4 4 0 0 1 4-4h2a4 4 0 0 1 4 4v2"/><path d="M14 20v-2a4 4 0 0 1 4-4h2a4 4 0 0 1 4 4v2"/></svg>
                    </div>
                </div>
            </a>
            <a href="{{ route('admin.projects') }}" class="stat-card-link">
                <div class="card stat-card stat-card-hover" style="background:#fff;color:#5B307E;border:2px solid #5B307E;">
                    <div class="stat-label" style="color:#5B307E;">Projets</div>
                    <div class="stat-value" style="font-size:2rem;color:#5B307E;">{{ $projectCount }}</div>
                    <div style="font-size:1.5rem;">
                        <svg width="28" height="28" fill="none" stroke="#5B307E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 7a2 2 0 0 1 2-2h4l2 3h8a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7z"/></svg>
                    </div>
                </div>
            </a>
            <a href="{{ route('admin.admins') }}" class="stat-card-link">
                <div class="card stat-card stat-card-hover" style="background:#fff;color:#5B307E;border:2px solid #5B307E;">
                    <div class="stat-label" style="color:#5B307E;">Admins</div>
                    <div class="stat-value" style="font-size:2rem;color:#5B307E;">{{ $adminCount }}</div>
                    <div style="font-size:1.5rem;">
                        <svg width="28" height="28" fill="none" stroke="#5B307E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M2 19h20M2 8l5 5 5-9 5 9 5-5"/></svg>
                    </div>
                </div>
            </a>
        </div>
        <div class="dashboard-stats-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:1.2rem 1.5rem;margin-bottom:2rem;">
            <a href="{{ route('admin.incidents') }}" class="stat-card-link">
                <div class="card stat-card stat-card-hover" style="background:#fff;color:#5B307E;border:2px solid #5B307E;">
                    <div class="stat-label" style="color:#5B307E;">Total</div>
                    <div class="stat-value" style="color:#5B307E;">{{ $totalIncidents }}</div>
                </div>
            </a>
            <a href="{{ route('admin.incidents', ['status'=>'Nouveau']) }}" class="stat-card-link">
                <div class="card stat-card stat-card-hover" style="background:#fff;color:#5B307E;border:2px solid #5B307E;">
                    <div class="stat-label" style="color:#5B307E;">Ouverts</div>
                    <div class="stat-value" style="color:#5B307E;">{{ $openCount }}</div>
                </div>
            </a>
            <a href="{{ route('admin.incidents', ['status'=>'En cours']) }}" class="stat-card-link">
                <div class="card stat-card stat-card-hover" style="background:#fff;color:#5B307E;border:2px solid #5B307E;">
                    <div class="stat-label" style="color:#5B307E;">En cours</div>
                    <div class="stat-value" style="color:#5B307E;">{{ $inProgressCount }}</div>
                </div>
            </a>
            <a href="{{ route('admin.incidents', ['status'=>'Résolu']) }}" class="stat-card-link">
                <div class="card stat-card stat-card-hover" style="background:#fff;color:#5B307E;border:2px solid #5B307E;">
                    <div class="stat-label" style="color:#5B307E;">Résolus</div>
                    <div class="stat-value" style="color:#5B307E;">{{ $resolvedCount }}</div>
                </div>
            </a>
            <a href="{{ route('admin.incidents', ['status'=>'Fermé']) }}" class="stat-card-link">
                <div class="card stat-card stat-card-hover" style="background:#fff;color:#5B307E;border:2px solid #5B307E;">
                    <div class="stat-label" style="color:#5B307E;">Fermés</div>
                    <div class="stat-value" style="color:#5B307E;">{{ $closedCount }}</div>
                </div>
            </a>
        </div>
        <div class="dashboard-graphs-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:1.5rem;align-items:stretch;">
            <a href="{{ route('admin.incidents') }}" class="stat-card-link">
                <div class="card p-3 h-100 stat-card-hover" style="cursor:pointer;min-height:220px;display:flex;flex-direction:column;justify-content:center;background:#fff;color:#5B307E;border:2px solid #5B307E;">
                    <h6 class="mb-2">Incidents par mois</h6>
                    <canvas id="incidentsByMonthChart" height="70"></canvas>
                </div>
            </a>
            <a href="{{ route('admin.incidents') }}" class="stat-card-link">
                <div class="card p-3 h-100 stat-card-hover" style="cursor:pointer;min-height:220px;display:flex;flex-direction:column;justify-content:center;background:#fff;color:#5B307E;border:2px solid #5B307E;">
                    <h6 class="mb-2">Incidents par équipe</h6>
                    <canvas id="incidentsByTeamChart" height="70"></canvas>
                </div>
            </a>
        </div>
        
        <!-- Export Data Section -->
        <div class="export-section" style="margin-top:2rem;background:linear-gradient(135deg,#fff 0%,#f8f9ff 100%);border-radius:16px;box-shadow:0 8px 32px rgba(91,48,126,0.08);border:1px solid rgba(91,48,126,0.1);overflow:hidden;">
            <div class="export-header" style="background:linear-gradient(135deg, #5B307E, #764ba2);color:#fff;padding:1.2rem 1.5rem;font-weight:600;font-size:1.1rem;">Export des données</div>
            <div class="export-content" style="padding:1.5rem;">
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin-bottom:1rem;">
                    <div class="export-card" style="background:#fff;border-radius:12px;padding:1.2rem;border:1px solid #e2e8f0;text-align:center;transition:transform 0.2s;cursor:pointer;" onclick="exportWithFormat('incidents')">
                        <div style="font-size:2rem;margin-bottom:0.5rem;">
                            <svg width="32" height="32" fill="none" stroke="#5B307E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                        </div>
                        <div style="font-weight:600;color:#5B307E;margin-bottom:0.3rem;">Incidents</div>
                        <div style="font-size:0.9rem;color:#666;">Excel / PDF</div>
                    </div>
                    <div class="export-card" style="background:#fff;border-radius:12px;padding:1.2rem;border:1px solid #e2e8f0;text-align:center;transition:transform 0.2s;cursor:pointer;" onclick="exportWithFormat('clients')">
                        <div style="font-size:2rem;margin-bottom:0.5rem;">
                            <svg width="32" height="32" fill="none" stroke="#5B307E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20v-1a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4v1"/></svg>
                        </div>
                        <div style="font-weight:600;color:#5B307E;margin-bottom:0.3rem;">Clients</div>
                        <div style="font-size:0.9rem;color:#666;">Excel / PDF</div>
                    </div>
                    <div class="export-card" style="background:#fff;border-radius:12px;padding:1.2rem;border:1px solid #e2e8f0;text-align:center;transition:transform 0.2s;cursor:pointer;" onclick="exportWithFormat('employees')">
                        <div style="font-size:2rem;margin-bottom:0.5rem;">
                            <svg width="32" height="32" fill="none" stroke="#5B307E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20v-1a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4v1"/><path d="M12 12v4l2 2l-2-2l-2 2l2-2v-4z"/></svg>
                        </div>
                        <div style="font-weight:600;color:#5B307E;margin-bottom:0.3rem;">Employés</div>
                        <div style="font-size:0.9rem;color:#666;">Excel / PDF</div>
                    </div>
                    <div class="export-card" style="background:#fff;border-radius:12px;padding:1.2rem;border:1px solid #e2e8f0;text-align:center;transition:transform 0.2s;cursor:pointer;" onclick="exportWithFormat('projects')">
                        <div style="font-size:2rem;margin-bottom:0.5rem;">
                            <svg width="32" height="32" fill="none" stroke="#5B307E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 7a2 2 0 0 1 2-2h4l2 3h8a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7z"/></svg>
                        </div>
                        <div style="font-weight:600;color:#5B307E;margin-bottom:0.3rem;">Projets</div>
                        <div style="font-size:0.9rem;color:#666;">Excel / PDF</div>
                    </div>
                </div>
                <div style="text-align:center;margin-top:1rem;">
                    <button onclick="exportAllData()" style="background:linear-gradient(135deg,#FFC107 0%,#FFD54F 100%);color:#5B307E;padding:0.8rem 2rem;border:none;border-radius:8px;font-weight:600;cursor:pointer;transition:background 0.2s;font-size:1rem;display:flex;align-items:center;justify-content:center;gap:0.5rem;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7,10 12,15 17,10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Exporter toutes les données
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Incidents by Month
const monthLabels = @json($incidentsByMonth->pluck('month'));
const monthData = @json($incidentsByMonth->pluck('count'));
const ctxMonth = document.getElementById('incidentsByMonthChart').getContext('2d');
new Chart(ctxMonth, {
    type: 'line',
    data: {
        labels: monthLabels,
        datasets: [{
            label: 'Incidents',
            data: monthData,
            backgroundColor: 'rgba(91,48,126,0.12)',
            borderColor: '#5B307E',
            borderWidth: 2,
            fill: true,
            tension: 0.3,
            pointRadius: 3,
            pointBackgroundColor: '#764ba2',
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 } }
        }
    }
});
// Incidents by Team
const teamLabels = @json($incidentsByTeam->pluck('team'));
const teamData = @json($incidentsByTeam->pluck('count'));
const ctxTeam = document.getElementById('incidentsByTeamChart').getContext('2d');
new Chart(ctxTeam, {
    type: 'bar',
    data: {
        labels: teamLabels,
        datasets: [{
            label: 'Incidents',
            data: teamData,
            backgroundColor: '#FFC107',
            borderRadius: 8,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 } }
        }
    }
});
// Add hover effect for stat cards
const statCards = document.querySelectorAll('.stat-card-hover');
statCards.forEach(card => {
    card.addEventListener('mouseenter', () => card.style.boxShadow = '0 8px 32px rgba(91,48,126,0.18)');
    card.addEventListener('mouseleave', () => card.style.boxShadow = '0 2px 8px rgba(91,48,126,0.08)');
});
// Change chart colors to purple and gold
const monthChart = Chart.getChart(ctxMonth);
if(monthChart) {
    monthChart.data.datasets[0].borderColor = '#5B307E';
    monthChart.data.datasets[0].backgroundColor = 'rgba(91,48,126,0.12)';
}
const teamChart = Chart.getChart(ctxTeam);
if(teamChart) {
    teamChart.data.datasets[0].backgroundColor = '#FFC107';
}

// Export functionality
function exportData(type, format) {
    const url = `/admin/export/${type}/${format}`;
    window.open(url, '_blank');
}

function exportAllData() {
    const types = ['incidents', 'clients', 'employees', 'projects'];
    const format = 'excel';
    
    types.forEach((type, index) => {
        setTimeout(() => {
            exportData(type, format);
        }, index * 500);
    });
}

// Add format selection for individual exports
function exportWithFormat(type) {
    const format = confirm('Choisir le format:\nOK = Excel (CSV)\nAnnuler = PDF (HTML)') ? 'excel' : 'pdf';
    exportData(type, format);
}

// Add hover effects for export cards
document.querySelectorAll('.export-card').forEach(card => {
    card.addEventListener('mouseenter', () => {
        card.style.transform = 'translateY(-2px)';
        card.style.boxShadow = '0 4px 16px rgba(91,48,126,0.15)';
    });
    card.addEventListener('mouseleave', () => {
        card.style.transform = 'translateY(0)';
        card.style.boxShadow = 'none';
    });
});
</script>
<style>
.stat-card {
    min-height: 90px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border-radius: 14px;
    box-shadow: 0 2px 8px rgba(91,48,126,0.08);
    transition: box-shadow 0.2s;
    cursor: pointer;
    padding: 1.1rem 0.5rem;
}
.stat-label {
    font-size: 1rem;
    font-weight: 500;
    margin-bottom: 0.2em;
}
.stat-value {
    font-size: 1.3rem;
    font-weight: 700;
}
.stat-card-link {
    text-decoration: none;
}
@media (max-width: 900px) {
    .dashboard-stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .dashboard-graphs-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection