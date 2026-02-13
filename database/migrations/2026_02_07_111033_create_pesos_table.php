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
        Schema::create('pesos', function (Blueprint $table) {
            $table->id();

            $table->string('origen');
            $table->timestamp('sync_at')->nullable();
            $table->double('nro_salida_origen');

            $table->dateTime('Horas')->nullable();
            $table->dateTime('Fechas')->nullable();
            $table->dateTime('Fechai')->nullable();
            $table->dateTime('Horai')->nullable();

            $table->bigInteger('Pesoi')->nullable();
            $table->bigInteger('Pesos')->nullable();
            $table->bigInteger('Bruto')->nullable();
            $table->bigInteger('Tara')->nullable();
            $table->bigInteger('Neto')->nullable();

            $table->double('pesoguia')->nullable();

            $table->boolean('Destarado')->nullable();
            $table->boolean('anular')->nullable();
            $table->boolean('eje')->nullable();

            $table->text('Placa')->nullable();
            $table->text('Observacion')->nullable();
            $table->text('Producto')->nullable();
            $table->text('Conductor')->nullable();
            $table->text('Transportista')->nullable();
            $table->text('RazonSocial')->nullable();
            $table->text('Operadori')->nullable();
            $table->text('Operadors')->nullable();

            $table->text('carreta')->nullable();
            $table->text('guia')->nullable();
            $table->text('guiat')->nullable();
            $table->text('pedido')->nullable();
            $table->text('entrega')->nullable();
            $table->text('um')->nullable();

            $table->text('rucr')->nullable();
            $table->text('ruct')->nullable();
            $table->text('destino')->nullable();
            $table->text('origen')->nullable();
            $table->text('brevete')->nullable();
            $table->text('pbmax')->nullable();
            $table->text('tipo')->nullable();
            $table->text('centro')->nullable();
            $table->text('nia')->nullable();
            $table->text('bodega')->nullable();
            $table->text('ip')->nullable();
            $table->text('pesaje')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesos');
    }
};
