<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            $table->foreignId("cliente_id")->constrained("lq_clientes");
            $table->date("fecha_inicio_contrato")->nullable()->index();
            $table->date("fecha_fin_contrato")->nullable()->index();
            $table->string("recepcionado_cliente")->index()->nullable();
            $table->string("legalizado_jurgen")->index()->nullable();
            $table->string("numero_contrato")->nullable()->index();
            $table->string("observaciones")->nullable();
            $table->boolean("cercano_vencer")->default(false);
            $table->boolean("permitir_acceso")->default(false);
            $table->foreignId("usuario_id")->nullable()->constrained("users");
            $table->foreignId("usuario_edit_id")->nullable()->constrained("users");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};
