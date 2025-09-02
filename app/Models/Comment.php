<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';
    protected $fillable = [
        'content', 'user_id', 'incident_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function incident()
    {
        return $this->belongsTo(Incident::class, 'incident_id');
    }
}
