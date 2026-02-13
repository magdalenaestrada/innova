<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('recepciones_ingreso', function (Blueprint $table) {
            $table->id();

            $table->string('nro_salida')->index()->unique();

            $table->string('nro_ruc')->nullable();
            $table->string('documento_ruc')->nullable();

            $table->string('documento_encargado')->nullable();
            $table->string('datos_encargado')->nullable();
            $table->string('domicilio_encargado')->nullable();

            $table->string('dni_conductor')->nullable();
            $table->string('datos_conductor')->nullable();

            $table->text('observacion')->nullable();
            $table->json('extras')->nullable();

            $table->foreignId('representante_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('creado_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recepciones_ingreso');
    }
};
