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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('PESEQ');
            $table->date('PEFECHA');
            $table->date('PEDATE2');
            $table->string('PENUM');
            $table->string('PEALMACEN');
            $table->string('PEPAR0');
            $table->string('PEPAR1');
            $table->string('SUCURSAL');
            $table->string('SERIE');
            $table->string('CAPTURA')->nullable(); // Captura
            $table->string('ESTATUS'); // Estatus
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
