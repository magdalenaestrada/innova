<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepresentanteLegal extends Model
{
    use HasFactory;
    protected $table = "representantes_legales";
    protected $fillable = [
        "persona_id",
        "cliente_id",
    ];

    public function cliente()
    {
        return $this->belongsTo(LqCliente::class, "cliente_id");
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, "persona_id");
    }
}
