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
        Schema::create('clientes_codigo', function (Blueprint $table): void {
            $table->id();
            $table->string(column: 'documento');
            $table->string(column: 'nombre');
            $table->char(column: 'codigo', length: 6)->unique();
            $table->char(column: 'tipo_documento', length: 1);
            $table->unsignedBigInteger(column: 'creador_id');
            $table->timestamps();
            $table->foreign('creador_id')->references(column: 'id')->on(table: 'users')->onDelete(action: 'restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes_codigo');
    }
};
