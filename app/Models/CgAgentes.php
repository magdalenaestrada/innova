<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CgAgentes extends Model
{
    use HasFactory;

    protected $table = 'cg_agentes';

    protected $fillable = [
        'control_garita_id',
        'usuarios_id',
    ];

    public function controlGarita()
    {
        return $this->belongsTo(ControlGarita::class, foreignKey: 'control_garita_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, foreignKey: 'usuarios_id');
    }
}
