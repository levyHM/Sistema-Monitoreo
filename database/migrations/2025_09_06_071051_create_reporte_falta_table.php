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
        Schema::create('reporte_falta', function (Blueprint $table) {
            $table->id('idreporte_falta'); // autoincrement
            $table->string('cantidad', 45)->nullable();
            $table->string('numero_factura', 45)->nullable();
            $table->string('checo', 45)->nullable();
            $table->string('pendiente', 45)->nullable();

            // Foreign key a reporte_faltante
            $table->foreignId('reporte_faltante_idreporte_faltante')
                  ->constrained('reporte_faltante', 'idreporte_faltante')
                  ->onUpdate('no action')
                  ->onDelete('no action');

            // Foreign key a catalogo_producto
            $table->foreignId('catalogo_idcatalogo')
                  ->constrained('catalogo_producto', 'idcatalogoproducto')
                  ->onUpdate('no action')
                  ->onDelete('no action');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reporte_falta');
    }
};
