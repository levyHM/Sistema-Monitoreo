<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('concepto', function (Blueprint $table) {
            $table->increments('idconcepto');

            $table->boolean('devolucion')->nullable();
            $table->boolean('faltante')->nullable();
            $table->boolean('sobrante')->nullable();

            // El tipo debe coincidir con `recibos.idrecibos`
            $table->unsignedInteger('recibos_idrecibos');

            $table->index('recibos_idrecibos', 'fk_concepto_recibos1_idx');

            $table->foreign('recibos_idrecibos', 'fk_concepto_recibos1')
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
        Schema::dropIfExists('concepto');
    }
};
