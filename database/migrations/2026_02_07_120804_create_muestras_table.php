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
        Schema::create('muestras', function (Blueprint $table) {
            $table->id();
            $table->string("codigo")->unique()->index();
            $table->decimal("humedad", 5, 2)->nullable();
            $table->decimal("oro", 5, 2)->nullable();
            $table->decimal("plata", 5, 2)->nullable();
            $table->decimal("cobre", 5, 2)->nullable();
            $table->decimal("arsenico", 5, 2)->nullable();
            $table->decimal("antimonio", 5, 2)->nullable();
            $table->decimal("bismuto", 5, 2)->nullable();
            $table->decimal("plomo_zinc", 5, 2)->nullable();
            $table->decimal("mercurio", 5, 2)->nullable();
            $table->decimal("h2o", 5, 2)->nullable();
            $table->string("observacion")->nullable();
            $table->foreignId("ingreso_id")->constrained("ingresos_liquidaciones");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('muestras');
    }
};
