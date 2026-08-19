<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'barbearia_id',
        'name',
        'price',
    ];

    public function barbearia()
    {
        return $this->belongsTo(Barbearia::class);
    }

    public function atendimentos()
    {
        return $this->hasMany(Atendimento::class);
    }
}
