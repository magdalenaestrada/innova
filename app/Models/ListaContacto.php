<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ListaContacto extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "lista_contactos";
    protected $fillable = [
        "cliente_id",
        "celular",
        "solo_whatsapp",
    ];
    public function cliente()
    {
        return $this->belongsTo(LqCliente::class, "cliente_id");
    }
}
