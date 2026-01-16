<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleControlGarita extends Model
{
    use HasFactory;

    protected $table = 'detalle_control_garita';

    protected $fillable = [
        'tipo_movimiento',
        'tipo_entidad',
        'nombre',
        'documento',
        'tipo_documento',
        'ocurrencias',
        'hora',
        'destino',
        'placa',
        'tipo_carga',
        'tipo_vehiculo',
        'etiqueta_id',
        'control_garita_id',
        'usuario_id',
    ];

    protected $appends = ['trae_carga'];

    public function getTraeCargaAttribute()
    {
        return !empty($this->destino) || !empty($this->tipo_carga ? 1 : 0);
    }

    public function etiqueta()
    {
        return $this->belongsTo(Etiqueta::class, foreignKey: 'etiqueta_id');
    }

    public function controlGarita()
    {
        return $this->belongsTo(ControlGarita::class, foreignKey: 'control_garita_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, foreignKey: 'usuario_id');
    }
}
