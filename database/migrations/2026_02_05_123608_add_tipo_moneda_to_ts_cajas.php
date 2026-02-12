<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ts_cajas', function (Blueprint $table) {
            $table->unsignedBigInteger('tipo_moneda_id')
                  ->nullable()
                  ->after('nombre');
        });
    }

    public function down(): void
    {
        Schema::table('ts_cajas', function (Blueprint $table) {
            $table->dropColumn('tipo_moneda_id');
        });
    }
};
