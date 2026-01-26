<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        'tipo_mineral_id',
        'tipo_vehiculo_id',
        'etiqueta_id',
        'control_garita_id',
        'usuario_id',
    ];

    protected $appends = ['trae_carga'];

    public function getTraeCargaAttribute()
    {
        return !empty($this->destino) || !empty($this->tipo_carga ? 1 : 0);
    }

    protected function nombre(): Attribute
    {
        return Attribute::make(
            // get: fn ($value) => mb_convert_case($value, MB_CASE_TITLE, 'UTF-8'),
            set: fn ($value) => Str::upper($value),
        );
    }

    protected function ocurrencias(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value ? Str::upper($value) : '',
        );
    }

    protected function destino(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value ? Str::upper($value) : '',
        );
    }

    protected function placa(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value ? Str::upper($value) : '',
        );
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

    public function tipoMineral()
    {
        return $this->belongsTo(TipoMineral::class, foreignKey: 'tipo_mineral_id');
    }

    public function tipoVehiculo()
    {
        return $this->belongsTo(TipoVehiculo::class, foreignKey: 'tipo_vehiculo_id');
    }
}
