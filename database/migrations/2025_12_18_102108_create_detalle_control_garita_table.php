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
        Schema::create('detalle_control_garita', function (Blueprint $table) {
            $table->id();
            $table->char('tipo_movimiento', 1);
            $table->char('tipo_entidad', 1);
            $table->string('nombre');
            $table->string('documento');
            $table->char('tipo_documento', 1);
            $table->text('ocurrencias')->nullable();
            $table->time('hora');
            $table->string('destino')->nullable();
            $table->string('placa', 7)->unique()->nullable();
            $table->string('tipo_vehiculo')->nullable();
            // $table->unsignedBigInteger('vehiculo_id')->nullable();
            // $table->unsignedBigInteger('trabajador_id');
            $table->unsignedBigInteger('etiqueta_id')->nullable();
            $table->unsignedBigInteger('control_garita_id')->nullable();
            $table->timestamps();

            // $table->foreign('vehiculo_id')->references('id')->on('vehiculos')->onDelete('cascade');
            // $table->foreign('trabajador_id')->references('id')->on('empleados')->onDelete('cascade');
            $table->foreign('etiqueta_id')->references('id')->on('etiquetas')->onDelete('cascade');
            $table->foreign('control_garita_id')->references('id')->on('control_garita')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_control_garita');
    }
};
