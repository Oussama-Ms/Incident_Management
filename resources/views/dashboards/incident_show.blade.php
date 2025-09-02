@extends('layouts.app')

@section('content')
<div class="incident-details-container" style="width:100%;margin:0;background:linear-gradient(135deg,#fff 0%,#f8f9ff 100%);border-radius:24px;box-shadow:0 12px 40px rgba(91,48,126,0.10);padding:0;overflow:hidden;">
    <div class="incident-details-header" style="background:linear-gradient(135deg, var(--primary), var(--primary-hover));color:#fff;padding:2.5rem 2rem 2rem 2rem;position:relative;">
        <div style="display:flex;align-items:center;gap:1.5rem;flex-wrap:wrap;">
            <div style="flex:1;min-width:220px;">
                <h1 class="incident-details-title" style="font-size:2.2rem;font-weight:800;margin:0 0 0.3rem 0;letter-spacing:-1px;line-height:1.1;">{{ $incident->title }}</h1>
                <p class="incident-details-subtitle" style="color:#e0d6ee;font-size:1.1rem;margin:0;">Projet : <span style="color:#fff;font-weight:600;">{{ $incident->projet->nom ?? 'Non défini' }}</span></p>
            </div>
            <div style="display:flex;align-items:center;gap:0.7rem;">
                @php
                    $statusColors = [
                        'Nouveau' => ['bg' => '#1976d2', 'color' => '#fff'],
                        'En cours' => ['bg' => '#ffc107', 'color' => '#333'],
                        'Résolu' => ['bg' => '#28a745', 'color' => '#fff'],
                        'Fermé' => ['bg' => '#6c757d', 'color' => '#fff'],
                    ];
                    $badge = $statusColors[$incident->status ?? 'Nouveau'] ?? $statusColors['Nouveau'];
                    $badgeStyle = 'display:inline-block;padding:0.5em 1.2em;border-radius:999px;font-weight:700;font-size:1.08rem;background:' . $badge['bg'] . ';color:' . $badge['color'] . ';box-shadow:0 2px 8px rgba(91,48,126,0.10);';
                    if ($incident->status === 'Résolu') {
                        $badgeStyle = 'display:inline-block;padding:0.5em 1.2em;border-radius:999px;font-weight:700;font-size:1.08rem;background:#28a745;color:#fff;box-shadow:0 2px 8px rgba(91,48,126,0.10);';
                    }
                @endphp
                <span style="{{ $badgeStyle }}">{{ $incident->status }}</span>
            </div>
        </div>
    </div>
    <div class="incident-details-content" style="padding:2.5rem 2rem 0 2rem;">
        <div class="incident-info-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:2rem 2.5rem;margin-bottom:2.5rem;">
            <div class="incident-info-item">
                <div class="incident-info-label">Priorité</div>
                <div class="incident-info-value">{{ $incident->priority ?? 'Normale' }}</div>
            </div>
            <div class="incident-info-item">
                <div class="incident-info-label">Catégorie</div>
                <div class="incident-info-value">{{ $incident->category ?? 'Non définie' }}</div>
            </div>
            <div class="incident-info-item">
                <div class="incident-info-label">Contact</div>
                <div class="incident-info-value">{{ $incident->contact_phone ?? 'Non défini' }}</div>
            </div>
        </div>
        @if($incident->notes)
            <div class="card" style="background:#f7f7fa;padding:1.2rem 1.5rem;border-radius:12px;margin-bottom:2rem;border-left:5px solid var(--primary);">
                <div class="incident-info-label" style="margin-bottom:0.5rem;">Notes</div>
                <div class="incident-info-value" style="font-size:1.08rem;font-weight:400;color:#333;line-height:1.6;">{{ $incident->notes }}</div>
            </div>
        @endif
        @if($incident->files->isNotEmpty())
            <div class="card" style="background:#f7f7fa;padding:1.2rem 1.5rem;border-radius:12px;margin-bottom:2rem;border-left:5px solid var(--primary);">
                <div class="incident-info-label" style="margin-bottom:0.5rem;">Fichiers joints</div>
                <div class="incident-info-value" style="font-size:1.08rem;font-weight:400;color:#333;">
                    @foreach ($incident->files as $file)
                        @php
                            $fileName = basename($file->filename);
                            $fileUrl = asset('storage/' . $file->filename);
                        @endphp
                        <a href="{{ $fileUrl }}" target="_blank" style="display:inline-block;margin-right:1rem;color:#5B307E;text-decoration:none;padding:0.5rem 1rem;background:#fff;border-radius:6px;border:1px solid #e1e5e9;transition:box-shadow 0.2s;box-shadow:0 2px 8px rgba(91,48,126,0.06);font-weight:600;" onclick="console.log('File URL: {{ $fileUrl }}')">{{ $fileName }}</a>
                    @endforeach
                </div>
            </div>
        @endif
                @if($incident->projet && $incident->projet->sla)
            @php
                $creation = $incident->creationdate ? $incident->creationdate : null;
                $responseDeadline = $creation && $incident->projet->sla->responseTime ? $creation->copy()->addHours($incident->projet->sla->responseTime) : null;
                $resolutionDeadline = $creation && $incident->projet->sla->resolutionTime ? $creation->copy()->addHours($incident->projet->sla->resolutionTime) : null;
                
                // Ensure deadlines are in the correct timezone
                if ($responseDeadline) {
                    $responseDeadline = $responseDeadline->setTimezone('Africa/Casablanca');
                }
                if ($resolutionDeadline) {
                    $resolutionDeadline = $resolutionDeadline->setTimezone('Africa/Casablanca');
                }
            @endphp
            <div style="background:#fff;border-radius:16px;box-shadow:0 8px 32px rgba(91,48,126,0.08);margin-bottom:2.5rem;overflow:hidden;">
                <div style="background:linear-gradient(135deg, var(--primary), var(--primary-hover));color:#fff;padding:1.2rem 1.5rem;font-weight:700;font-size:1.1rem;letter-spacing:0.5px;border-radius:16px 16px 0 0;">Contrat SLA du projet</div>
                <div style="padding:2rem 1.5rem;display:grid;grid-template-columns:1fr 1fr;gap:1.5rem 2.5rem;align-items:center;">
                    <div>
                        <div style="color:#888;font-weight:600;">Délai de réponse</div>
                        <div style="font-size:1.15rem;font-weight:700;color:var(--primary);margin-bottom:0.3rem;">{{ $incident->projet->sla->responseTime }} heures</div>
                        @if($responseDeadline)
                            <div style="margin-top:0.5rem;color:#5B307E;font-weight:600;background:#fff;padding:0.5em 1em;border-radius:8px;display:inline-block;box-shadow:0 2px 8px rgba(91,48,126,0.06);">
                                Temps restant : <span id="sla-response-countdown"></span>
                            </div>
                        @endif
                    </div>
                    <div>
                        <div style="color:#888;font-weight:600;">Délai de résolution</div>
                        <div style="font-size:1.15rem;font-weight:700;color:var(--primary);margin-bottom:0.3rem;">{{ $incident->projet->sla->resolutionTime }} heures</div>
                        @if($resolutionDeadline)
                            <div style="margin-top:0.5rem;color:#5B307E;font-weight:600;background:#fff;padding:0.5em 1em;border-radius:8px;display:inline-block;box-shadow:0 2px 8px rgba(91,48,126,0.06);">
                                Temps restant : <span id="sla-resolution-countdown"></span>
                            </div>
                        @endif
                    </div>
                    <div style="grid-column:1/-1;">
                        <div style="color:#888;font-weight:600;">Priorité SLA</div>
                        <div style="font-size:1.1rem;font-weight:500;color:#333;">{{ $incident->projet->sla->priority }}</div>
                    </div>
                </div>
            </div>
            <script>
            function updateCountdown(id, deadline) {
                function pad(n) { return n<10 ? '0'+n : n; }
                
                function calc() {
                    // Get current time in the same timezone as the server
                    var now = new Date().getTime();
                    var end = new Date(deadline).getTime();
                    var diff = end - now;
                    
                    if (diff <= 0) {
                        document.getElementById(id).textContent = 'Délai dépassé';
                        document.getElementById(id).style.color = '#dc3545';
                    } else {
                        var h = Math.floor(diff / (1000*60*60));
                        var m = Math.floor((diff % (1000*60*60)) / (1000*60));
                        var s = Math.floor((diff % (1000*60)) / 1000);
                        document.getElementById(id).textContent = pad(h)+":"+pad(m)+":"+pad(s);
                        document.getElementById(id).style.color = '#5B307E';
                    }
                }
                calc();
                setInterval(calc, 1000);
            }
            

            @if($responseDeadline)
            updateCountdown('sla-response-countdown', '{{ $responseDeadline->toISOString() }}');
            @endif
            @if($resolutionDeadline)
            updateCountdown('sla-resolution-countdown', '{{ $resolutionDeadline->toISOString() }}');
            @endif
            </script>
        @endif
        <!-- Conversations Section -->
        <div class="card" style="padding:0;overflow:hidden;border-radius:16px;box-shadow:0 8px 32px rgba(91,48,126,0.08);margin-bottom:2.5rem;background:#fff;">
            <div style="background:linear-gradient(135deg, var(--primary), var(--primary-hover));color:#fff;padding:1.2rem 1.5rem;font-weight:700;font-size:1.1rem;border-bottom:1px solid #ececec;border-radius:16px 16px 0 0;">Conversations récentes</div>
            <div style="max-height:400px;overflow-y:auto;padding:1.5rem;">
                @if($incident->comments->isEmpty())
                    <div style="text-align:center;color:#888;padding:2rem;">
                        <p>Aucun message pour le moment</p>
                    </div>
                @else
                    @foreach ($incident->comments as $comment)
                        @php
                            $isCurrentUser = $comment->user->id === Auth::id();
                            $isEmployee = $comment->user->role === 'employee';
                        @endphp
                        <div style="margin-bottom:1rem;display:flex;{{ $isCurrentUser ? 'justify-content:flex-end;' : 'justify-content:flex-start;' }}">
                            <div style="max-width:70%;background:#f7f7fa;color:#333;border-radius:12px;padding:0.8rem 1rem;box-shadow:0 2px 4px rgba(91,48,126,0.04);">
                                <div style="font-size:0.95rem;margin-bottom:0.3rem;font-weight:600;color:#5B307E;">
                                    {{ $comment->user->name }}
                                    @if($isEmployee)
                                        <span style="background:#5B307E;color:#fff;padding:0.2rem 0.5rem;border-radius:10px;font-size:0.7rem;margin-left:0.5rem;">Employé</span>
                                    @else
                                        <span style="background:#28a745;color:#fff;padding:0.2rem 0.5rem;border-radius:10px;font-size:0.7rem;margin-left:0.5rem;">Client</span>
                                    @endif
                                </div>
                                <div style="line-height:1.5;font-size:1.05rem;">{{ $comment->content }}</div>
                                <div style="font-size:0.85rem;margin-top:0.5rem;opacity:0.7;">{{ $comment->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div style="border-top:1px solid #eee;padding:1.5rem;background:#fafafa;">
                <form action="{{ route('comments.store', $incident->id) }}" method="POST">
                    @csrf
                    <div style="display:flex;gap:0.5rem;align-items:flex-end;">
                        <div style="flex:1;">
                            <label for="content" style="font-size:0.95rem;color:#666;margin-bottom:0.3rem;display:block;font-weight:600;">Votre message</label>
                            <textarea id="content" name="content" rows="2" class="form-control" style="width:100%;padding:0.8rem;border:1.5px solid #e1e5e9;border-radius:8px;resize:none;font-family:inherit;" required placeholder="Tapez votre message ici..."></textarea>
                        </div>
                        <button type="submit" class="btn chat-send-btn" style="padding:0 1.5rem;border-radius:8px;background:#5B307E;color:#fff;border:none;cursor:pointer;font-weight:600;display:flex;align-items:center;">Envoyer</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Status Update Section - Only for clients and only to 'Résolu' -->
        @if(Auth::user()->role === 'client' && $incident->status !== 'Résolu')
            <div class="status-update-section" style="margin-bottom:2.5rem;background:linear-gradient(135deg,#fff 0%,#f8f9ff 100%);border-radius:16px;box-shadow:0 8px 32px rgba(91,48,126,0.08);border:1px solid rgba(91,48,126,0.08);overflow:hidden;">
                <div class="status-update-header" style="background:linear-gradient(135deg, #5B307E, #7B4A9E);color:#fff;padding:1.2rem 1.5rem;font-weight:600;font-size:1.1rem;">Marquer comme résolu</div>
                <div class="status-update-content" style="padding:1.5rem;">
                    <form action="{{ route('incidents.updateStatus', $incident->id) }}" method="POST" class="status-form" style="display:flex;gap:1.2rem;align-items:center;">
                        @csrf
                        @method('PATCH')
                        <div style="flex: 1;">
                            <label for="status" style="display:block;font-weight:600;margin-bottom:0.5rem;color:#333;">Statut actuel: <span style="color:#5B307E;">{{ $incident->status }}</span></label>
                            <select id="status" name="status" class="status-select" style="width:100%;padding:0.7rem 1.2rem;border-radius:8px;border:1.5px solid #e1e5e9;">
                                <option value="Résolu">Résolu</option>
                            </select>
                        </div>
                        <button type="submit" class="status-submit-btn" style="background:#5B307E;color:#fff;padding:0.8rem 2rem;border:none;border-radius:8px;font-weight:600;cursor:pointer;">Marquer comme résolu</button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection 

<script>
// Ensure the Envoyer button matches the textarea height
function syncChatButtonHeight() {
  var textarea = document.querySelector('.form-control[name="content"]');
  var btn = document.querySelector('.chat-send-btn');
  if (textarea && btn) {
    btn.style.height = textarea.offsetHeight + 'px';
  }
}
window.addEventListener('DOMContentLoaded', syncChatButtonHeight);
window.addEventListener('resize', syncChatButtonHeight);
document.querySelector('.form-control[name="content"]').addEventListener('input', syncChatButtonHeight);
</script> 