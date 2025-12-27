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
        'tipo_vehiculo',
        // 'vehiculos_id',
        // 'trabajador_id',
        'etiqueta_id',
        'control_garita_id',
    ];

    // public function vehiculo()
    // {
    //     return $this->belongsTo(Vehiculo::class, foreignKey: 'vehiculos_id');
    // }

    // public function trabajador()
    // {
    //     return $this->belongsTo(Empleado::class, foreignKey: 'trabajador_id');
    // }   

    public function etiqueta()
    {
        return $this->belongsTo(Etiqueta::class, foreignKey: 'etiqueta_id');
    }

    public function controlGarita()
    {
        return $this->belongsTo(ControlGarita::class, foreignKey: 'control_garita_id');
    }
}
