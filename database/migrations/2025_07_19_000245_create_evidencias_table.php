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
        Schema::create('evidencias', function (Blueprint $table) {
            $table->increments('idevidencias'); // llave primaria auto-incremental
            $table->string('url', 255)->nullable();
            $table->integer('estatus')->nullable();
            $table->unsignedInteger('recibos_idrecibos');

            $table->timestamps(); // created_at y updated_at automáticos

            // Llave foránea a tabla recibos
            $table->foreign('recibos_idrecibos')
                ->references('idrecibos')->on('recibos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->index('recibos_idrecibos', 'fk_evidencias_recibos1_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evidencias');
    }
};
