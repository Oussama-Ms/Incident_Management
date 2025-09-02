<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'file';
    public $timestamps = false;
    protected $fillable = [
        'filename', 'size', 'uploaddate', 'user-id', 'incident-id'
    ];

    public function incident()
    {
        return $this->belongsTo(Incident::class, 'incident-id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user-id');
    }
}
