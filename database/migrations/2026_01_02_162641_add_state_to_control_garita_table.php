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
        Schema::table('control_garita', function (Blueprint $table) {
            $table->string('unidad')->nullable()->after('fecha');
            $table->enum('estado', ['activo', 'finalizado'])->default('activo')->after('unidad');
            $table->time('hora_inicio')->nullable()->after('estado');
            $table->time('hora_fin')->nullable()->after('hora_inicio');
            $table->unsignedBigInteger('usuario_id')->nullable()->after('hora_fin');

            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('control_garita', function (Blueprint $table) {
            $table->dropForeign(['usuario_id']);
            $table->dropColumn(['unidad', 'estado', 'hora_inicio', 'hora_fin', 'usuario_id']);
        });
    }
};
