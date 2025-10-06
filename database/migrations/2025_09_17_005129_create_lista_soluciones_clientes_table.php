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
     Schema::create('lista_soluciones_clientes', function (Blueprint $table) {
            $table->id('idlista_soluciones_clientes');
            $table->unsignedBigInteger('reporte_soluciones_clientes_idreporte_soluciones_clientes');
            $table->unsignedBigInteger('catalogo_soluciones_clientes_idCatalogoSolucionesClientes');
            $table->integer('cantidad')->nullable();
            $table->double('total')->nullable();
            $table->string('observaciones', 100)->nullable();
            $table->integer('estatus')->nullable();
            $table->timestamps();

            // Índices explícitos
            $table->index(
                'catalogo_soluciones_clientes_idCatalogoSolucionesClientes',
                'fk_lista_soluciones_clientes_catalogo_soluciones_clientes1_idx'
            );

            $table->index(
                'reporte_soluciones_clientes_idreporte_soluciones_clientes',
                'fk_lista_soluciones_clientes_reporte_soluciones_clientes1_idx'
            );

            // Claves foráneas
            $table->foreign(
                'catalogo_soluciones_clientes_idCatalogoSolucionesClientes',
                'fk_lista_soluciones_clientes_catalogo_soluciones_clientes1'
            )->references('idCatalogoSolucionesClientes')
             ->on('catalogo_soluciones_clientes')
             ->onDelete('no action')
             ->onUpdate('no action');

            $table->foreign(
                'reporte_soluciones_clientes_idreporte_soluciones_clientes',
                'fk_lista_soluciones_clientes_reporte_soluciones_clientes1'
            )->references('idreporte_soluciones_clientes')
             ->on('reporte_soluciones_clientes')
             ->onDelete('no action')
             ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lista_soluciones_clientes');
    }
};
