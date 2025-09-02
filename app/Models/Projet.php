<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Projet extends Model
{
    protected $table = 'projet';
    protected $fillable = ['nom', 'description', 'sla_id', 'startDate', 'endDate', 'status', 'Client_id', 'team_id'];
    public $timestamps = false;

    public function client()
    {
        return $this->belongsTo(User::class, 'Client_id');
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class, 'projet_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function sla()
    {
        return $this->belongsTo(Sla::class, 'sla_id');
    }
}
