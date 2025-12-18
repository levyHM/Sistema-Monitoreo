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
        Schema::create('firma', function (Blueprint $table) {
            $table->increments('idfirma');
            $table->unsignedBigInteger('id_user')->nullable();
            $table->unsignedInteger('catalogo_firma_idcatalogo_firma');
            $table->string('observaciones', 45)->nullable();
            $table->integer('estatus')->nullable();
            $table->unsignedInteger('recibos_idrecibos');

            // Foreign keys
            $table->foreign('catalogo_firma_idcatalogo_firma', 'fk_firma_catalogo_firma1')
                ->references('idcatalogo_firma')
                ->on('catalogo_firma')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('recibos_idrecibos', 'fk_firma_recibos1')
                ->references('idrecibos')
                ->on('recibos')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('id_user')
                ->references('id')
                ->on('users')
                ->onDelete('set null')
                ->onUpdate('cascade');

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('firma');
    }
};
