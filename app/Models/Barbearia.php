<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barbearia extends Model
{
    protected $fillable = [
        'admin_id',
        'name',
        'municipio',
        'plano',
        'gestor',
        'email',
        'number',
        'password',
        'isactive',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
