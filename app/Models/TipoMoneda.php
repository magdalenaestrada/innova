<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoMoneda extends Model
{
    use HasFactory;

    protected $table = 'tipo_moneda';
    public function cajas()
    {
        return $this->hasMany(TsCaja::class, 'tipo_moneda_id');
    }

    public function cuentas()
    {
        return $this->hasMany(TsCuenta::class, 'tipo_moneda_id');
    }


}

