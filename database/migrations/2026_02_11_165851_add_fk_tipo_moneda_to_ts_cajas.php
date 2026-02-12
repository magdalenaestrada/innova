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
       Schema::table('ts_cajas', function (Blueprint $table) {
                $table->foreign('tipo_moneda_id')
                    ->references('id')
                    ->on('tipo_moneda')
                    ->onDelete('restrict');
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ts_cajas', function (Blueprint $table) {
            //
        });
    }
};
