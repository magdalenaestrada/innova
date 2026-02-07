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
        Schema::create('lista_contactos', function (Blueprint $table) {
            $table->id();
            $table->foreignId("cliente_id")->constrained("lq_clientes");
            $table->string("celular");
            $table->boolean("solo_whatsapp");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lista_contactos');
    }
};
