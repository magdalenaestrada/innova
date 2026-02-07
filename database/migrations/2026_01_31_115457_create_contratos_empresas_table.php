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
        Schema::create('contratos_empresas', function (Blueprint $table) {
            $table->id();
            $table->foreignId("contrato_id")->constrained("contratos");
            $table->foreignId("empresa_id")->constrained("ts_empresas");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contratos_empresas');
    }
};
