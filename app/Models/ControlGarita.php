<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ControlGarita extends Model
{
    use HasFactory;

    protected $table = 'control_garita';

    protected $fillable = [
        'turno',
        'fecha',
        'unidad',
        'estado',
        'hora_inicio',
        'hora_fin',
        'usuario_id',
    ];

    protected function unidad(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Str::ucfirst(Str::lower($value)),
            set: fn ($value) => Str::upper($value),
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class, foreignKey: 'usuario_id');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleControlGarita::class, foreignKey: 'control_garita_id');
    }
    
    public function agentes()
    {
        return $this->belongsToMany(
            User::class,
            'cg_agentes',
            'control_garita_id',
            'usuarios_id');
    }

    public function cargos()
    {
        return $this->belongsToMany(
            Producto::class, 
            'cg_cargos', 
            'control_garita_id',
            'productos_id'
        )->withPivot('cantidad');
    }
}
