<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContratosEmpresas extends Model
{
    use HasFactory;
    protected $table = "contratos_empresas";
    protected $fillable = [
        "contrato_id",
        "empresa_id"
    ];

    public function contrato()
    {
        return $this->belongsTo(Contrato::class, "contrato_id");
    }
    public function empresa()
    {
        return $this->belongsTo(TsEmpresa::class, "empresa_id");
    }
}
