<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteExcelGarita extends Model
{
    use HasFactory;

    protected $table = 'vw_reporte_excel_garita';

    protected $fillable = [];

    public $incrementing = false;

    public $timestamps = false;

    public function save(array $options = []) { return false; }

    public function scopeTipoMovimiento($query, $tipo)
    {
        if (empty($tipo)) return $query;

        $map = [
            'E' => 'ENTRADA',
            'S' => 'SALIDA'
        ];

        $valor = $map[$tipo] ?? $tipo;

        return $query->where('TIPO MOVIMIENTO', $valor);
    }

    public function scopeTipoEntidad($query, $tipo)
    {
        if (empty($tipo)) return $query;

        $map = [
            'P' => 'PERSONA',
            'V' => 'VEHÍCULO'
        ];

        $valor = $map[$tipo] ?? $tipo;

        return $query->where('TIPO ENTIDAD', $valor);
    }

    public function scopeFechaBetween($query, $fechaInicio, $fechaFin)
    {
        if ($fechaInicio && $fechaFin) {
            return $query->whereBetween('FECHA', [$fechaInicio, $fechaFin]);
        }
        
        return $query;
    }

    public function scopeUsuario($query, $id)
    {
        if (empty($id)) return $query;

        return $query->where('ID USUARIO', $id);
    }

    public function scopeHoraBetween($query, $horaInicio, $horaFin)
    {
        if (!empty($horaInicio) && !empty($horaFin)) {
            return $query->whereBetween('HORA', [$horaInicio, $horaFin]);
        }
        
        return $query;
    }
}
