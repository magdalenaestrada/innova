<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('detalle_control_garita', function (Blueprint $table) {
            $table->renameColumn('tipo_carga', 'tipo_mineral_id');
            $table->renameColumn('tipo_vehiculo', 'tipo_vehiculo_id');
        });
        Schema::table('detalle_control_garita', function (Blueprint $table) {
            $table->unsignedBigInteger('tipo_mineral_id')->nullable()->change();
            $table->unsignedBigInteger('tipo_vehiculo_id')->nullable()->change();
        });
        Schema::table('detalle_control_garita', function (Blueprint $table) {
            $table->foreign('tipo_mineral_id')->references('id')->on('tipo_mineral')->onDelete('cascade');
            $table->foreign('tipo_vehiculo_id')->references('id')->on('tipos_vehiculos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_control_garita', function (Blueprint $table) {
            $table->dropForeign(['tipo_mineral_id']);
            $table->dropForeign(['tipo_vehiculo_id']);
        });
        
        try {
        Schema::table('detalle_control_garita', function (Blueprint $table) {
            $table->dropIndex('detalle_control_garita_tipo_mineral_id_foreign');
            $table->dropIndex('detalle_control_garita_tipo_vehiculo_id_foreign');
        });
        } catch (\Exception $e) {}

        Schema::table('detalle_control_garita', function (Blueprint $table) {
            $table->string('tipo_mineral_id')->nullable()->change();
            $table->string('tipo_vehiculo_id')->nullable()->change();
        });
        Schema::table('detalle_control_garita', function (Blueprint $table) {
            $table->renameColumn('tipo_mineral_id', 'tipo_carga');
            $table->renameColumn('tipo_vehiculo_id', 'tipo_vehiculo');
        });
    }
};
