<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesos_otras_bal', function (Blueprint $table) {
            $table->id();
            $table->timestamp('fechai')->nullable()->index();
            $table->timestamp('fechas')->nullable()->index();
            $table->bigInteger('bruto')->nullable()->index();
            $table->bigInteger('tara')->nullable()->index();
            $table->unsignedBigInteger('neto')->default(0);
            $table->string('placa')->nullable()->index();
            $table->string('observacion')->nullable()->index();
            $table->string('producto')->nullable()->index();
            $table->string('conductor')->nullable()->index();
            $table->string('guia')->nullable()->index();
            $table->string('guiat')->nullable()->index();
            $table->string('origen')->nullable()->index();
            $table->string('destino')->nullable()->index();
            $table->string('balanza')->nullable()->index();
            $table->unsignedBigInteger('cliente_id')->index();
            $table->unsignedBigInteger('estado_id')->index();

            $table->foreign('cliente_id')->references('id')->on('lq_clientes')->onDelete('restrict');
            $table->foreign('estado_id')->references('id')->on('estados')->onDelete('restrict');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesos_otras_bal');
    }
};
