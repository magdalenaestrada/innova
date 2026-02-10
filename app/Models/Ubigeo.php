<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ubigeo extends Model
{
    protected $table = 'ubigeo';
    
    protected $fillable = [
        'nombre', 
        'codigo_postal', 
        'nivel', 
        'parent_id'
    ];

    /**
     * RELACIONES RECURSIVAS
     */

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Ubigeo::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Ubigeo::class, 'parent_id');
    }

    /**
     * SCOPES
     * Para usar: Ubigeo::departamentos()->get();
     */

    public function scopeDepartamentos($query)
    {
        return $query->where('nivel', 1);
    }

    public function scopeProvincias($query)
    {
        return $query->where('nivel', 2);
    }

    public function scopeDistritos($query)
    {
        return $query->where('nivel', 3);
    }

    /**
     * ACCESOR
     * Para obtener la ruta completa: "Lima / Lima / Miraflores"
     * Se usa llamando a $ubigeo->nombre_completo
     */
    public function getNombreCompletoAttribute()
    {
        $nombre = $this->nombre;
        $padre = $this->parent;

        while ($padre) {
            $nombre = $padre->nombre . ' / ' . $nombre;
            $padre = $padre->parent;
        }

        return $nombre;
    }
}