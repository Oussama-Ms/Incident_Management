<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sla extends Model
{
    use HasFactory;
    protected $table = 'sla';
    protected $fillable = [
        'responseTime',
        'resolutionTime',
        'priority',
    ];

    public $timestamps = false;

    public function projet()
    {
        return $this->hasOne(Projet::class, 'sla_id');
    }
} 