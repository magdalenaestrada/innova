<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TipoMineral extends Model
{
    use HasFactory;

    protected $table = 'tipo_mineral';

    protected $fillable = [
        'nombre'
        ];

    protected function nombre(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Str::ucfirst(Str::lower($value)),
            set: fn ($value) => Str::upper($value),
        );
    }

    public function detalles()
    {
        return $this->hasMany(DetalleControlGarita::class, foreignKey: 'tipo_mineral_id');
    }
}
