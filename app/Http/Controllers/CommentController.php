<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Incident;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CommentController extends Controller
{
    public function store(Request $request, $incident_id)
    {
        $user = Auth::user();
        $incident = Incident::findOrFail($incident_id);

        $validated = $request->validate([
            'content' => 'required|string|max:999',
        ]);

        $comment = Comment::create([
            'content' => $validated['content'],
            'user_id' => $user->id,
            'incident_id' => $incident->id,
        ]);

        // If the commenter is an employee, notify the client
        if ($user->role === 'employee') {
            $client = $incident->user;
            $notification = Notification::create([
                'user_id' => $client->id,
                'sender_id' => $user->id,
                'incident_id' => $incident->id,
                'type' => 'incident_reply',
                'message' => 'Réponse de ' . $user->name . ': "' . $comment->content . '" concernant l\'incident: ' . $incident->title,
                'is_read' => false,
            ]);

            // Send email notification
            Mail::send([], [], function ($message) use ($client, $user, $incident, $comment) {
                $body = '<p>Bonjour ' . $client->name . ',</p>' .
                    '<p><strong>' . $user->name . '</strong> a répondu à votre incident : <strong>' . $incident->title . '</strong>.</p>' .
                    '<blockquote style="background:#f9f9f9;padding:1em;border-left:4px solid #5B307E;">' . nl2br(e($comment->content)) . '</blockquote>' .
                    '<p><a href="' . url('/incidents/' . $incident->id) . '" style="color:#5B307E;font-weight:bold;">Voir la conversation</a></p>';
                $message->to($client->email)
                    ->subject('Nouvelle réponse à votre incident: ' . $incident->title)
                    ->html($body);
            });
        } else if ($user->role === 'client') {
            // Notify all employees in the same team as the incident's project
            $teamId = $incident->projet->team_id ?? null;
            if ($teamId) {
                $employees = \App\Models\Employee::where('team_id', $teamId)->get();
                foreach ($employees as $employee) {
                    Notification::create([
                        'user_id' => $employee->user_id,
                        'sender_id' => $user->id,
                        'incident_id' => $incident->id,
                        'type' => 'incident_reply',
                        'message' => 'Message de ' . $user->name . ': "' . $comment->content . '" concernant l\'incident: ' . $incident->title,
                        'is_read' => false,
                    ]);
                }
            }
        }

        return back()->with('success', 'Commentaire ajouté avec succès!');
    }
}
