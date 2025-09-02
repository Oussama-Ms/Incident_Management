<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'user';
    public $timestamps = false;
    protected $fillable = ['name', 'email', 'password', 'role'];
    protected $hidden = ['password'];

    public function employee()
    {
        return $this->hasOne(Employee::class, 'user_id');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isEmployee()
    {
        return $this->role === 'employee';
    }

    public function isClient()
    {
        return $this->role === 'client';
    }
}