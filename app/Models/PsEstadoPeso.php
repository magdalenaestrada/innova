<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PsEstadoPeso extends Model
{
    protected $table = 'ps_estados_pesos';

    protected $fillable = ['peso_id', 'estado_id', 'origen', 'sync_at'];

    public function estado()
    {
        return $this->belongsTo(PsEstado::class, 'estado_id', 'id');
    }
    public function peso()
    {
        return $this->belongsTo(Peso::class, 'peso_id', 'id');
    }
}
