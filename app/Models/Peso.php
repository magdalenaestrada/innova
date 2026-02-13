<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peso extends Model
{
    protected $table = 'pesos';

    protected $fillable = [
        'Horas',
        'Fechas',
        'Fechai',
        'Horai',
        'Pesoi',
        'Pesos',
        'Bruto',
        'Tara',
        'Neto',
        'Placa',
        'Observacion',
        'Producto',
        'Conductor',
        'Transportista',
        'RazonSocial',
        'Operadori',
        'Destarado',
        'Operadors',
        'carreta',
        'guia',
        'guiat',
        'pedido',
        'entrega',
        'um',
        'pesoguia',
        'rucr',
        'ruct',
        'destino',
        'origen',
        'brevete',
        'pbmax',
        'tipo',
        'centro',
        'nia',
        'bodega',
        'ip',
        'anular',
        'eje',
        'pesaje'
    ];
}
