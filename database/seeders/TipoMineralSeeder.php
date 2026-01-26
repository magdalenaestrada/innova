<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoMineralSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipo_mineral')->upsert([
            ['id' => 1, 'nombre' => 'CHANCADO'],
            ['id' => 2, 'nombre' => 'A GRANEL'],
            ['id' => 3, 'nombre' => 'MOLIDO'],
            ['id' => 4, 'nombre' => 'CONCENTRADO'],
            ['id' => 5, 'nombre' => 'RELAVE'],
        ],
        ['id'],
        ['nombre']
        );
    }
}
