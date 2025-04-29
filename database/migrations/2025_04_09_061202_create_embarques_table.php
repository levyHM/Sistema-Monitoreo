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
        Schema::create('embarques', function (Blueprint $table) {
            $table->id();
            $table->integer('ID_OPERADOR');
            $table->integer('ID_RUTA');
            $table->integer('ID_CAMIONETA');
            $table->date('FECHA');
            $table->string('ESCANER');
            $table->string('FACTURA');
            $table->integer('CANTIDAD');
            $table->integer('VALIDACION')->nullable();
            $table->string('CLIENTE');
            $table->string('SUCURSAL');
            $table->time('HORA_DE_LLEGADA');
            $table->time('HORA_DE_SALIDA');
            $table->text('OBSERVACIONES')->nullable();
            $table->string('ESTATUS');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('embarques');
    }
};
