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
        Schema::create('requerimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId("sociedad_cliente_id")->constrained("lq_sociedad_cliente");
            $table->integer("igv")->default(18);
            $table->decimal("merma", 5, 2)->default(0);
            $table->decimal("pagable_oro", 5, 2)->default(0);
            $table->decimal("pagable_plata", 5, 2)->default(0);
            $table->decimal("pagable_cobre", 5, 2)->default(0);
            $table->decimal("proteccion_oro", 5, 2)->default(0);
            $table->decimal("proteccion_plata", 5, 2)->default(0);
            $table->decimal("proteccion_cobre", 5, 2)->default(0);
            $table->decimal("deduccion_oro", 5, 2)->default(0);
            $table->decimal("deduccion_plata", 5, 2)->default(0);
            $table->decimal("deduccion_cobre", 5, 2)->default(0);
            $table->decimal("refinamiento_oro", 5, 2)->default(0);
            $table->decimal("refinamiento_plata", 5, 2)->default(0);
            $table->decimal("refinamiento_cobre", 5, 2)->default(0);
            $table->decimal("maquila", 5, 2)->default(0);
            $table->decimal("analisis", 5, 2)->default(0);
            $table->decimal("estibadores", 5, 2)->default(0);
            $table->decimal("molienda", 5, 2)->default(0);
            $table->decimal("penalidad_arsenico", 5, 2)->nullable();
            $table->decimal("penalidad_antimonio", 5, 2)->nullable();
            $table->decimal("penalidad_bismuto", 5, 2)->nullable();
            $table->decimal("penalidad_plomo_zinc", 5, 2)->nullable();
            $table->decimal("penalidad_mercurio", 5, 2)->nullable();
            $table->decimal("penalidad_h2o", 5, 2)->nullable();
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
        Schema::dropIfExists('requerimientos');
    }
};
