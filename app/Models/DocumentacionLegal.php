<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentacionLegal extends Model
{
    use HasFactory;

    protected $table = 'documentacion_legal';
    
    protected $fillable =[ 'documento_identificacion','codigo_documento' ];
}
