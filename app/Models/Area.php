<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $table = 'areas';

    protected $fillable = ['nombre', 'creador_id'];

    public function diaLibre() 
    {
        return $this->hasMany(DiaLibre::class, foreignKey: 'areas_id');
    }
}
