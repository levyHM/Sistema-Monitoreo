<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id('ID'); // ID
            $table->string('DITIPMV'); // DITIPMV
            $table->string('DNUM'); // DNUM
            $table->date('DFECHA'); // DFECHA
            $table->string('CLICOD'); // CLICOD
            $table->string('DPAR1'); // DPAR1
            $table->time('DHORA'); // DHORA
            $table->string('SERIE'); // DNUM + CLICOD
            $table->string('CAPTURA')->nullable(); // Captura
            $table->string('ESTATUS'); // Estatus
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facturas');
    }
}
