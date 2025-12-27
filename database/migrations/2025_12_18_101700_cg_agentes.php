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
        Schema::create('cg_agentes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuarios_id');
            $table->unsignedBigInteger('control_garita_id');
            $table->timestamps();

            $table->foreign('usuarios_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('control_garita_id')->references('id')->on('control_garita')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cg_agentes');
    }
};
