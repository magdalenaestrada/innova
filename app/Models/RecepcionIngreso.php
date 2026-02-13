<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class RecepcionIngreso extends Model
{
    protected $table = 'recepciones_ingreso';

    protected $fillable = [
        'nro_salida',
        'nro_ruc', 'documento_ruc',
        'documento_encargado', 'datos_encargado', 'domicilio_encargado',
        'dni_conductor', 'datos_conductor',
        'observacion', 'extras',
        'representante_user_id',
        'creado_por',
    ];

    protected $casts = [
        'extras' => 'array',
        'creado_por' => 'integer',
        'representante_user_id' => 'integer',
    ];

    // Usuario que creó el registro (auditoría)
    public function creador()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    // Representante seleccionado para el acta
    public function representante()
    {
        return $this->belongsTo(User::class, 'representante_user_id');
    }
}
