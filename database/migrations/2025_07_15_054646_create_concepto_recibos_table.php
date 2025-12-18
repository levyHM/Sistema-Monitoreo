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
        Schema::create('concepto_recibos', function (Blueprint $table) {
            $table->id('idconcepto_recibos'); // BIGINT UNSIGNED AUTO_INCREMENT

            $table->string('numero_factura', 45)->nullable();
            $table->integer('cantidad')->nullable();

            $table->unsignedBigInteger('productos_idproductos');
            $table->unsignedInteger('recibos_idrecibos');

            $table->string('facturado', 45)->nullable();
            $table->string('fisico', 45)->nullable();
            $table->string('observaciones', 45)->nullable();

            // Índices explícitos
            $table->index('productos_idproductos', 'fk_concepto_recibos_productos1_idx');
            $table->index('recibos_idrecibos', 'fk_concepto_recibos_recibos1_idx');

            // Claves foráneas corregidas
            $table->foreign('productos_idproductos', 'fk_concepto_recibos_productos1')
                ->references('idproductos')
                ->on('productos')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('recibos_idrecibos', 'fk_concepto_recibos_recibos1')
                ->references('idrecibos')
                ->on('recibos')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concepto_recibos');
    }
};
