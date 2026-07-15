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

    public function getNextBillingDateAttribute()
    {
        if (!$this->created_at) {
            return now()->addDays(30);
        }
        
        $daysSinceCreation = $this->created_at->diffInDays(now());
        $cycles = floor($daysSinceCreation / 30);
        
        return $this->created_at->copy()->addDays(($cycles + 1) * 30);
    }

    public function getDaysUntilExpirationAttribute()
    {
        return now()->startOfDay()->diffInDays($this->next_billing_date->startOfDay(), false);
    }
}
