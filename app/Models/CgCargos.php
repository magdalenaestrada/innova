<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CgCargos extends Model
{
    use HasFactory;

    protected $table = 'cg_cargos';

    protected $fillable = [
        'nombre',
        'productos_id',
        'control_garita_id',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, foreignKey: 'productos_id');
    }

    public function controlGarita()
    {
        return $this->belongsTo(ControlGarita::class, foreignKey: 'control_garita_id');
    }

}
