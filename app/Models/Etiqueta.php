<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Etiqueta extends Model
{
    use HasFactory;

    protected $table = 'etiquetas';

    protected $fillable = [
        'nombre',
        'descripcion',
        'color',
    ];

    protected function nombre(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Str::ucfirst(Str::lower($value)),
            set: fn ($value) => $value ? Str::upper($value) : '',
        );
    }
    
    protected function descripcion(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value ? Str::upper($value) : '',
        );
    }
    
    protected function color(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value ? Str::upper($value) : '',
        );
    }

    public function detalles()
    {
        return $this->hasMany(DetalleControlGarita::class, foreignKey: 'etiqueta_id');
    }
}
