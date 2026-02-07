<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesoOtraBal extends Model
{
    use HasFactory;

    protected $table = 'pesos_otras_bal';

    protected $fillable = [
        'fechai',
        'fechas',
        'bruto',
        'tara',
        'neto',
        'placa',
        'observacion',
        'producto',
        'conductor',
        'guia',
        'guiat',
        'origen',
        'destino',
        'balanza',
        'cliente_id',
        'estado_id',
        'usuario_id',
    ];

    public function cliente()
    {
        return $this->belongsTo(LqCliente::class, 'cliente_id');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
