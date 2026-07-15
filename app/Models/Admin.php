<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'isactive',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function barbearias()
    {
        return $this->hasMany(Barbearia::class);
    }
}
