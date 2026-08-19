<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Atendimento extends Model
{
    protected $fillable = [
        'user_id',
        'service_id',
        'pagamento_id',
        'valor',
        'horario',
    ];

    protected function casts(): array
    {
        return [
            'horario' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function pagamento()
    {
        return $this->belongsTo(Pagamento::class);
    }
}
