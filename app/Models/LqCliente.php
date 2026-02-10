<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LqCliente extends Model
{
    use HasFactory;
    protected $table = 'lq_clientes';
    protected $fillable = [
        'documento',
        'nombre',
        'creador_id',
        'nombre_r_info',
        'r_info_prestado',
        'r_info',
        'codigo_minero',
        'nombre_minero',
        'ubigeo_id',
        'estado_reinfo',
        'estado',
        'observacion',
        'codigo'
    ];

    protected $casts = [
        'r_info_prestado' => 'boolean',
    ];

    protected function nombre_minero(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value ? Str::upper($value) : '',
        );
    }

    public function contrato()
    {
        return $this->hasOne(Contrato::class, "cliente_id");
    }

    public function representantes()
    {
        return $this->hasMany(RepresentanteLegal::class, "cliente_id");
    }

    public function contactos()
    {
        return $this->hasMany(ListaContacto::class, "cliente_id");
    }

    public function ubigeo()
    {
        return $this->belongsTo(Ubigeo::class, 'ubigeo_id');
    }
}
