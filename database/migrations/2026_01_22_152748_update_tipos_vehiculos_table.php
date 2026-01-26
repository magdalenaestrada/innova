<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tipos_vehiculos', function (Blueprint $table) {
            $table->dropUnique(['nombre_vehiculo']);
        });
        Schema::table('tipos_vehiculos', function (Blueprint $table) {
            $table->renameColumn('nombre_vehiculo', 'nombre');
            $table->renameColumn('descripcion_vehiculo', 'descripcion');
        });
        Schema::table('tipos_vehiculos', function (Blueprint $table) {
            $table->string('nombre')->unique()->nullable()->change();
        });

        Artisan::call('db:seed', [
            '--class' => 'TipoVehiculoSeeder',
            '--force' => true
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tipos_vehiculos', function (Blueprint $table) {
            $table->dropUnique(['nombre']);
        });
        Schema::table('tipos_vehiculos', function (Blueprint $table) {
            $table->renameColumn('nombre', 'nombre_vehiculo');
            $table->renameColumn('descripcion', 'descripcion_vehiculo');
        });
        Schema::table('tipos_vehiculos', function (Blueprint $table) {
            $table->string('nombre_vehiculo')->unique()->nullable(false)->change();
        });
    }
};
