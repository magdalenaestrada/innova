<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlGarita extends Model
{
    use HasFactory;

    protected $table = 'control_garita';

    protected $fillable = [
        'turno',
        'fecha',
    ];
}
