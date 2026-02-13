<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lote extends Model
{
    use SoftDeletes;
    protected $table = 'lotes';
    protected $fillable = ['codigo', 'nombre', 'activo', 'lq_cliente_id', 'usuario_id', 'origen', 'sync_at'];
    public function cliente()
    {
        return $this->belongsTo(LqCliente::class, 'lq_cliente_id');
    }
    public function pesos()
    {
        return $this->hasManyThrough(
            Peso::class,
            PsLotePeso::class,
            'lote_id',
            'NroSalida',
            'id',
            'peso_id'
        );
    }
}
