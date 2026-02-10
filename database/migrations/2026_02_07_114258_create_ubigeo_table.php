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
        Schema::create('ubigeo', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('codigo_postal')->nullable()->unique();
            $table->tinyInteger('nivel')->default(1); // 1:departamento 2:provincia 3:distrito
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();

            $table->index(['parent_id', 'nivel']);
            $table->index('codigo_postal');
            $table->index('nombre');
            $table->foreign('parent_id')->references('id')->on('ubigeo')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ubigeo');
    }
};
