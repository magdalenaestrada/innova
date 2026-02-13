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
        Schema::create('liquidacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId("cliente_id")->constrained("lq_clientes");
            $table->foreignId("cliente_ingreso_id")->constrained("lq_clientes");
            $table->foreignId("humedad_id")->constrained("humedades");
            $table->foreignId("requerimiento_id")->constrained("requerimientos");
            $table->decimal("cotizacion_oro", 10, 2)->nullable();
            $table->decimal("cotizacion_plata", 10, 2)->nullable();
            $table->decimal("cotizacion_cobre", 10, 2)->nullable();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('liquidacion');
    }
};
