<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('representantes_legales', function (Blueprint $table) {
            $table->id();
            $table->foreignId("persona_id")->constrained("personas");
            $table->foreignId("cliente_id")->constrained("lq_clientes");
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('representantes_legales');
    }
};
