<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LqCliente extends Model
{
    use HasFactory;
    protected $table = 'lq_clientes';
    protected $fillable = [
        'documento',
        'nombre',
        'creador_id',
        'r_info',
        'fecha_inicio_contrato',
        'fecha_fin_contrato',
        'estado',
        'observacion',
        'codigo',
        'r_info_prestado'
    ];
}
