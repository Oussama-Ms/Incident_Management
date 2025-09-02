<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $table = 'incident';
    protected $fillable = [
        'title', 'description', 'priority', 'category', 'due_date', 'contact_phone', 'location', 'notes', 'status', 'creationdate', 'updatedate', 'resolveddate', 'user_id', 'projet_id'
    ];
    public $timestamps = false;

    protected $casts = [
        'creationdate' => 'datetime',
        'updatedate' => 'datetime',
        'resolveddate' => 'datetime',
        'due_date' => 'datetime',
    ];

    public function projet()
    {
        return $this->belongsTo(Projet::class, 'projet_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function files()
    {
        return $this->hasMany(File::class, 'incident-id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'incident_id');
    }

    public function employee()
    {
        // Adjust the foreign key if your schema uses a different field
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
