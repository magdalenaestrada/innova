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
        Schema::create('dias_libres', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empleados_id');
            $table->unsignedBigInteger('areas_id');
            $table->date('dia_inicio');
            $table->date('dia_fin');
            $table->timestamps();

            $table->foreign('empleados_id')->references('id')->on('empleados')->onDelete('cascade');
            $table->foreign('areas_id')->references('id')->on('areas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dias_libres');
    }
};
