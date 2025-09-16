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
        Schema::create('catalogo_producto', function (Blueprint $table) {
            $table->id('idcatalogoproducto'); // autoincremental
            $table->string('ditipmv', 45)->nullable();
            $table->string('dnum', 45)->nullable();
            $table->string('dpar1', 45)->nullable();
            $table->dateTime('dhora')->nullable();
            $table->string('icod', 45)->nullable();
            $table->string('clicod', 45)->nullable();
            $table->decimal('clidesc10', 10, 2)->nullable();
            $table->decimal('aiprecio', 10, 5)->nullable();
            $table->decimal('aicant', 10, 3)->nullable();
            $table->string('idescr', 150)->nullable();
            $table->tinyInteger('estatus')->default(1); // 1: activo, 0: faltante
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogo_producto');
    }
};
