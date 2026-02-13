<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalles_ingresos', function (Blueprint $table) {
            $table->id();
            $table->foreignId("ingreso_id")->constrained("ingresos_liquidaciones");
            $table->foreignId("peso_id")->constrained("pesos");
            $table->string("peso_type");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalles_ingresos');
    }
};
