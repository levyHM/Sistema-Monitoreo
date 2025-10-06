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
        Schema::create('reporte_soluciones_clientes', function (Blueprint $table) {
            $table->id('idreporte_soluciones_clientes');
            $table->date('fecha')->nullable();
            $table->unsignedBigInteger('catalogo_clientes_idcatalogo_clientes');
            $table->boolean('devolucion')->nullable();
            $table->boolean('garantia')->nullable();
            $table->string('firma_cliente', 45)->nullable();
            $table->integer('firma_soluciones')->nullable();
            $table->integer('firma_credito')->nullable();
            $table->double('total')->nullable();
            $table->integer('descuento')->nullable();
            $table->double('subtotal')->nullable();
            $table->double('iva')->nullable();
            $table->double('total_completo')->nullable();
            $table->string('observaciones', 100)->nullable();
            $table->timestamps();

            $table->index('catalogo_clientes_idcatalogo_clientes', 'fk_reporte_soluciones_clientes_catalogo_clientes_idx');

            $table->foreign('catalogo_clientes_idcatalogo_clientes', 'fk_reporte_soluciones_clientes_catalogo_clientes')
                ->references('idcatalogo_clientes')
                ->on('catalogo_clientes')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reporte_soluciones_clientes');
    }
};
