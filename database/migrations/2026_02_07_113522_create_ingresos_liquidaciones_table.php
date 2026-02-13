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
        Schema::create('ingresos_liquidaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId("sociedad_cliente_id")->constrained("lq_sociedad_cliente");
            $table->string("codigo")->unique()->index();
            $table->foreignId("estado_mineral_id")->constrained("estados_mineral")->index();
            $table->date("fecha_ingreso")->index();
            $table->string("tolva");
            $table->string("req_analisis_lab_peru");
            $table->string("req_analisis_nasca_lab");
            $table->foreignId("creador_id")->constrained("users");
            $table->foreignId("editor_id")->constrained("users");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingresos');
    }
};
