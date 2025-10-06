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
        Schema::create('catalogo_soluciones_clientes', function (Blueprint $table) {
            $table->id('idCatalogoSolucionesClientes');
            $table->string('ditipmv', 45)->nullable();
            $table->string('dnum', 45)->nullable();
            $table->string('dpar1', 45)->nullable();
            $table->dateTime('dhora')->nullable();
            $table->string('icod', 45); // Ya no es único individualmente
            $table->string('clicod', 45)->nullable();
            $table->string('clidesc10', 45)->nullable();
            $table->double('aiprecio')->nullable();
            $table->double('aicant')->nullable();
            $table->string('idescr', 240)->nullable();
            $table->string('observaciones', 100)->nullable();
            $table->integer('estatus')->nullable();
            $table->timestamps();

            // Índice único compuesto
            $table->unique(['icod', 'dnum', 'clicod'], 'uq_soluciones_compuesta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogo_soluciones_clientes');
    }
};
