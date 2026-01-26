<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TipoVehiculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipos_vehiculos')->upsert([
            ['id' => 1, 'nombre' => 'AUTOMOVIL'],
            ['id' => 2, 'nombre' => 'MINIVAN'],
            ['id' => 3, 'nombre' => 'CAMIONETA'],
            ['id' => 4, 'nombre' => 'FURGONETA'],
            ['id' => 5, 'nombre' => 'CAMION'],
            ['id' => 6, 'nombre' => 'VOLQUETE'],
            ['id' => 7, 'nombre' => 'ENCAPSULADO'],
            ['id' => 8, 'nombre' => 'TRAILER'],
        ],
        ['id'],
        ['nombre']
        );
    }
}
