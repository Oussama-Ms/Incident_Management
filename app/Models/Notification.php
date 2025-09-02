<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    public $timestamps = false;
    protected $fillable = [
        'user_id', 'sender_id', 'incident_id', 'type', 'message', 'is_read'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function incident()
    {
        return $this->belongsTo(Incident::class, 'incident_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
