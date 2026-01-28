<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiaLibre extends Model
{
    use HasFactory;

    protected $table = 'dias_libres';

    protected $fillable = [
        'empleados_id',
        'areas_id',
        'dia_inicio',
        'dia_fin',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, foreignKey: 'empleados_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, foreignKey: 'areas_id');
    }
}
