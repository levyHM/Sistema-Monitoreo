<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recibos', function (Blueprint $table) {
            // Asumimos que quieres autoincremento:
            $table->increments('idrecibos');

            $table->enum('tipo_recibo', ['D', 'F'])->nullable();
            $table->enum('sucursal', ['P', 'PV','PO'])->nullable();
            $table->date('fecha')->nullable();

            // Debe coincidir con tipo generado en la tabla `catalogo_provedores`
            $table->unsignedInteger('provedores_idprovedores');

            // Clave foránea ajustada
            $table->foreign('provedores_idprovedores', 'fk_recibos_provedores1')
                  ->references('idcatalogo_provedores')
                  ->on('catalogo_provedores')
                  ->onDelete('no action')
                  ->onUpdate('no action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recibos');
    }
};
