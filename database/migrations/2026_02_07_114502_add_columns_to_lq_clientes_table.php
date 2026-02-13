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
        Schema::table('lq_clientes', function (Blueprint $table) {
            $table->string('codigo_minero')->nullable()->after('nombre_r_info');
            $table->string('nombre_minero')->nullable()->after('codigo_minero');
            $table->unsignedBigInteger('ubigeo_id')->nullable()->after('nombre_minero');
            $table->boolean('estado_reinfo')->nullable()->after('ubigeo_id');

            $table->foreign('ubigeo_id')->references('id')->on('ubigeo')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void    
    {
        Schema::table('lq_clientes', function (Blueprint $table) {
            $table->dropForeign(['ubigeo_id']);
            $table->dropColumn(['codigo_minero', 'nombre_minero', 'ubigeo_id', 'estado_reinfo']);
        });
    }
};
