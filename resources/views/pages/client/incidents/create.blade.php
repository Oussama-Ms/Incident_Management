@extends('layouts.app')

@section('content')
<div class="dashboard-header">
    <h1>Créer un Incident</h1>
    <p style="color:#666;margin-top:0.5rem;">Signalez un nouveau problème ou demandez de l'assistance</p>
</div>

@if (session('success'))
    <div class="alert alert-success" style="background:#d4edda;color:#155724;padding:1rem;border-radius:8px;margin-bottom:1.5rem;border-left:4px solid #28a745;">{{ session('success') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger" style="background:#f8d7da;color:#721c24;padding:1rem;border-radius:8px;margin-bottom:1.5rem;border-left:4px solid #dc3545;">
        <ul style="margin:0;padding-left:1.5rem;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div>
    <form action="{{ route('incidents.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- Basic Information Section -->
        <div class="card" style="margin-bottom:2rem;border-radius:12px;overflow:hidden;">
            <div style="background:#5B307E;color:#fff;padding:1rem 1.5rem;font-weight:600;">
                Informations de base
            </div>
            <div style="padding:1.5rem;">
                <div style="margin-bottom:1.5rem;">
                    <label for="title" style="display:block;font-weight:600;margin-bottom:0.5rem;color:#333;">Titre de l'incident *</label>
                    <input type="text" id="title" name="title" style="width:100%;padding:0.8rem;border:2px solid #e1e5e9;border-radius:8px;font-size:1rem;transition:border-color 0.3s;" required placeholder="Décrivez brièvement le problème...">
                </div>
                <div style="margin-bottom:1.5rem;">
                    <label for="description" style="display:block;font-weight:600;margin-bottom:0.5rem;color:#333;">Description détaillée *</label>
                    <textarea id="description" name="description" rows="5" style="width:100%;padding:0.8rem;border:2px solid #e1e5e9;border-radius:8px;font-size:1rem;resize:vertical;transition:border-color 0.3s;" required placeholder="Détaillez le problème, les étapes pour le reproduire, et tout contexte utile..."></textarea>
                </div>
                <div style="margin-bottom:1.5rem;">
                    <label for="projet_id" style="display:block;font-weight:600;margin-bottom:0.5rem;color:#333;">Projet concerné *</label>
                    <select id="projet_id" name="projet_id" style="width:100%;padding:0.8rem;border:2px solid #e1e5e9;border-radius:8px;font-size:1rem;background:#fff;transition:border-color 0.3s;" required>
                        <option value="">-- Sélectionner un projet --</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="sla-details" style="display:none;margin-top:1.2rem;padding:1rem;background:#f7f7fa;border-radius:8px;border:1.5px solid #e1e5e9;">
                    <div style="font-weight:600;color:#5B307E;margin-bottom:0.5rem;">Contrat SLA du projet sélectionné :</div>
                    <div><span style="font-weight:500;">Délai de réponse :</span> <span id="sla-response"></span> heures</div>
                    <div><span style="font-weight:500;">Délai de résolution :</span> <span id="sla-resolution"></span> heures</div>
                    <div><span style="font-weight:500;">Priorité :</span> <span id="sla-priority"></span></div>
                </div>
            </div>
        </div>

        <!-- Classification Section -->
        <div class="card" style="margin-bottom:2rem;border-radius:12px;overflow:hidden;">
            <div style="background:#5B307E;color:#fff;padding:1rem 1.5rem;font-weight:600;">
                Classification
            </div>
            <div style="padding:1.5rem;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
                    <div>
                        <label for="priority" style="display:block;font-weight:600;margin-bottom:0.5rem;color:#333;">Priorité</label>
                        <select id="priority" name="priority" style="width:100%;padding:0.8rem;border:2px solid #e1e5e9;border-radius:8px;font-size:1rem;background:#fff;">
                            <option value="Low">Faible</option>
                            <option value="Normal" selected>Normale</option>
                            <option value="High">Haute</option>
                            <option value="Critical">Critique</option>
                        </select>
                    </div>
                    <div>
                        <label for="category" style="display:block;font-weight:600;margin-bottom:0.5rem;color:#333;">Catégorie</label>
                        <select id="category" name="category" style="width:100%;padding:0.8rem;border:2px solid #e1e5e9;border-radius:8px;font-size:1rem;background:#fff;">
                            <option value="">-- Sélectionner une catégorie --</option>
                            <option value="Hardware">Matériel</option>
                            <option value="Software">Logiciel</option>
                            <option value="Network">Réseau</option>
                            <option value="Access">Accès</option>
                            <option value="Other">Autre</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Details Section -->
        <div class="card" style="margin-bottom:2rem;border-radius:12px;overflow:hidden;">
            <div style="background:#5B307E;color:#fff;padding:1rem 1.5rem;font-weight:600;">
                Détails supplémentaires
            </div>
            <div style="padding:1.5rem;">
                <div style="margin-bottom:1.5rem;">
                    <label for="contact_phone" style="display:block;font-weight:600;margin-bottom:0.5rem;color:#333;">Téléphone de contact</label>
                    <input type="text" id="contact_phone" name="contact_phone" style="width:100%;padding:0.8rem;border:2px solid #e1e5e9;border-radius:8px;font-size:1rem;" placeholder="+212 6 12 34 56 78">
                </div>
                <div>
                    <label for="notes" style="display:block;font-weight:600;margin-bottom:0.5rem;color:#333;">Notes supplémentaires</label>
                    <textarea id="notes" name="notes" rows="3" style="width:100%;padding:0.8rem;border:2px solid #e1e5e9;border-radius:8px;font-size:1rem;resize:vertical;" placeholder="Informations complémentaires, contexte particulier..."></textarea>
                </div>
            </div>
        </div>

        <!-- File Upload Section -->
        <div class="card" style="margin-bottom:2rem;border-radius:12px;overflow:hidden;">
            <div style="background:#5B307E;color:#fff;padding:1rem 1.5rem;font-weight:600;">
                Pièces jointes
            </div>
            <div style="padding:1.5rem;">
                <div>
                    <label for="media" style="display:block;font-weight:600;margin-bottom:0.5rem;color:#333;">Ajouter des fichiers</label>
                    <div style="border:2px dashed #e1e5e9;border-radius:8px;padding:2rem;text-align:center;background:#f7f7fa;">
                        <input type="file" id="media" name="media[]" multiple accept=".pdf,.jpeg,.jpg,.png,.gif,.doc,.docx,.txt" style="display:none;">
                        <label for="media" style="cursor:pointer;display:inline-block;font-weight:600;color:#5B307E;">Cliquez pour sélectionner des fichiers</label>
                        <div style="color:#666;font-size:0.9rem;margin-top:0.5rem;">PDF, JPEG, PNG, GIF, DOC, DOCX, TXT (max 20MB par fichier)</div>
                    </div>
                    <div id="file-list" style="margin-top:1rem;"></div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div style="text-align:center;">
            <button type="submit" style="background:#5B307E;color:#fff;border:none;padding:1rem 3rem;border-radius:8px;font-size:1.1rem;font-weight:600;cursor:pointer;transition:background-color 0.3s;box-shadow:0 4px 12px rgba(91,48,126,0.08);">
                Soumettre l'incident
            </button>
        </div>
    </form>
</div>

<script>
// File upload preview
const mediaInput = document.getElementById('media');
if (mediaInput) {
    mediaInput.addEventListener('change', function(e) {
        const fileList = document.getElementById('file-list');
        fileList.innerHTML = '';
        Array.from(e.target.files).forEach(file => {
            const fileItem = document.createElement('div');
            fileItem.style.cssText = 'background:#f7f7fa;padding:0.8rem;border-radius:8px;margin-bottom:0.8rem;display:flex;align-items:center;justify-content:space-between;border:1px solid #e1e5e9;';
            fileItem.innerHTML = `
                <div style="display:flex;align-items:center;gap:0.8rem;">
                    <span style="font-weight:600;color:#5B307E;">${file.name}</span>
                    <span style="color:#666;font-size:0.9rem;">(${(file.size / 1024 / 1024).toFixed(2)} MB)</span>
                </div>
                <button type="button" onclick="removeFile(this)" style="background:#dc3545;color:#fff;border:none;padding:0.3rem 0.6rem;border-radius:4px;cursor:pointer;font-size:0.8rem;">×</button>
            `;
            fileList.appendChild(fileItem);
        });
    });
}

function removeFile(button) {
    const fileItem = button.parentElement;
    fileItem.remove();
    
    // Clear the file input
    const mediaInput = document.getElementById('media');
    mediaInput.value = '';
    
    // Clear the file list
    document.getElementById('file-list').innerHTML = '';
}
// Form field focus effects
Array.from(document.querySelectorAll('input, textarea, select')).forEach(field => {
    field.addEventListener('focus', function() {
        this.style.borderColor = '#5B307E';
    });
    field.addEventListener('blur', function() {
        this.style.borderColor = '#e1e5e9';
    });
});
const projectSlaData = @json($projects->mapWithKeys(fn($p) => [$p->id => $p->sla]))
document.getElementById('projet_id').addEventListener('change', function(e) {
    const sla = projectSlaData[this.value];
    const details = document.getElementById('sla-details');
    if (sla) {
        document.getElementById('sla-response').textContent = sla.responseTime;
        document.getElementById('sla-resolution').textContent = sla.resolutionTime;
        document.getElementById('sla-priority').textContent = sla.priority;
        details.style.display = '';
    } else {
        details.style.display = 'none';
    }
});
</script>
@endsection 