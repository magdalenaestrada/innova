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
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('placa')->nullable();
            $table->string('modelo')->nullable();
            $table->unsignedBigInteger('tipo_vehiculo_id')->nullable();
            $table->timestamps();

            $table->foreign('tipo_vehiculo_id')->references('id')->on('tipos_vehiculos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};  
