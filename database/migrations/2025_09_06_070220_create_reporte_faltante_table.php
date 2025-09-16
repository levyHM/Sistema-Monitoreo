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
        Schema::create('reporte_faltante', function (Blueprint $table) {
            $table->id('idreporte_faltante'); // autoincrement
            $table->string('folio', 45)->nullable();
            $table->date('fecha')->nullable();
            $table->string('recibe_reporte', 45)->nullable();

            // Foreign key a catalogo_faltante
            $table->foreignId('catalogo_faltante_idcatalogo_faltante')
                  ->constrained('catalogo_faltante', 'idcatalogo_faltante')
                  ->onUpdate('no action')
                  ->onDelete('no action');

            $table->string('motivo_faltante', 70)->nullable();
            $table->string('solucion', 45)->nullable();
            $table->boolean('procede')->nullable();
            $table->boolean('cambio_fisico')->nullable();
            $table->boolean('nc_servicio')->nullable();
            $table->string('autorizo', 45)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reporte_faltante');
    }
};
