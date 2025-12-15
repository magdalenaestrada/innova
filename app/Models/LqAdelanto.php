<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LqAdelanto extends Model
{
    use HasFactory;
    protected $table = 'lq_adelantos';
    protected $fillable = ['cerrado','descripcion','representante_cliente_nombre', 'representante_cliente_documento', 'tipo_cambio', 'sociedad_id', 'salida_cuenta_id', 'creador_id', 'abierto', 'cliente_id', 'fecha'];



    protected $with = ['salidacuenta', 'sociedad', 'cliente'];
    public function salidacuenta(){
        return $this->belongsTo(TsSalidaCuenta::class, 'salida_cuenta_id');
    }

    public function sociedad(){
        return $this->belongsTo(LqSociedad::class, 'sociedad_id');
    }



    public function creador(){
        return $this->belongsTo(User::class, 'creador_id');
    }


    public function cliente(){
        return $this->belongsTo(LqCliente::class, 'cliente_id');
    }




    public function liquidacion()
    {
        return $this->hasOneThrough(LqLiquidacion::class,            // Final Model
        LqLiquidacionAdelanto::class,     // Intermediate Model
        'adelanto_id',             // Foreign key on intermediate model
        'id',                         // Foreign key on final model (LqAdelanto)
        'id',                         // Local key on this model
        'liquidacion_id'                 // Local key on intermediate model
        );

    }

    
    protected $casts = [
        'fecha' => 'datetime',
    ];

  

}
