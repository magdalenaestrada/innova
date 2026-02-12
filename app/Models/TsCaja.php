<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TsCaja extends Model
{
    use HasFactory;

    protected $table = 'ts_cajas';

    protected $fillable = ['nombre', 'codigo', 'tipo_moneda_id', 'encargado_id', 'creador_id', 'balance'];

    public function encargados()
    {
        return $this->belongsToMany(
            Empleado::class, 
            'ts_encargados_cajas',     // Pivot table name
            'caja_id',                // Foreign key on the pivot table for TsCaja
            'encargado_id'             // Foreign key on the pivot table for Empleado
        );
    }

    public function salidascajas()
    {
        return $this->hasMany(TsSalidacaja::class, 'caja_id');
    }

    public function ingresoscaja()
    {
        return $this->hasMany(TsIngresoCaja::class, 'caja_id');
    }

    public function reposiciones()
    {
        return $this->hasMany(TsReposicioncaja::class, 'caja_id');
    }
    public function tipoMoneda()
    {
        return $this->belongsTo(\App\Models\TipoMoneda::class, 'tipo_moneda_id');
    }
    public function getSimboloMonedaAttribute()
    {
        $codigo = strtoupper($this->tipoMoneda->codigo ?? 'PEN'); // PEN / USD
        return $codigo === 'USD' ? '$' : 'S/.';
    }
}
