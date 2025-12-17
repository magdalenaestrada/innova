<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteCodigo extends Model
{
    use HasFactory;
    //El nombre de la tabla tiene que ser la misma de la migreación
    protected $table = 'clientes_codigo';
    
    protected $fillable =[ 'documento','nombre','codigo','creador_id','tipo_documento' ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'creador_id');
    }
}
