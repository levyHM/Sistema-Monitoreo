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
        Schema::create('catalogo_faltante', function (Blueprint $table) {
            $table->id('idcatalogo_faltante'); // autoincremental
            $table->string('codigo', 45)->unique(); // único
            $table->string('codigo_nombre', 100)->nullable();
            $table->string('zona', 45)->nullable();
            $table->tinyInteger('estatus')->default(1); // 1: activo, 0: faltante
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogo_faltante');
    }
};
