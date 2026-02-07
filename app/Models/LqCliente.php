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
        'nombre_r_info',
        'r_info_prestado',
        'r_info',
        'estado',
        'observacion',
        'codigo'
    ];

    protected $casts = [
        'r_info_prestado' => 'boolean',
    ];

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
}
