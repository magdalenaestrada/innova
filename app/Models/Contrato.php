<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contrato extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "contratos";
    protected $fillable = [
        "cliente_id",
        "fecha_inicio_contrato",
        "fecha_fin_contrato",
        "recepcionado_cliente",
        "legalizado_jurgen",
        "numero_contrato",
        "observaciones",
        "cercano_vencer",
        "permitir_acceso",
        "usuario_id",
        "usuario_edit_id",
        "numero_juegos"
    ];

    public function cliente()
    {
        return $this->belongsTo(LqCliente::class, "cliente_id");
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, "usuario_id");
    }
    public function usuario_edit()
    {
        return $this->belongsTo(User::class, "usuario_edit_id");
    }

    public function empresas()
    {
        return $this->belongsToMany(
            TsEmpresa::class,
            'contratos_empresas',
            'contrato_id',
            'empresa_id'
        );
    }
}
