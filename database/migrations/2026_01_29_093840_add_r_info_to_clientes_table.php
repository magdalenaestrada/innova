<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lq_clientes', function (Blueprint $table) {
            $table->boolean('r_info_prestado')->default(false);
            $table->string("r_info")->nullable();
            $table->string("nombre_r_info")->nullable();
            $table->char("estado")->nullable()->default("A");
            $table->string("observacion")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lq_clientes', function (Blueprint $table) {});
    }
};
