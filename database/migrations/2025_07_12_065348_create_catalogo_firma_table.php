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

        Schema::create('catalogo_firma', function (Blueprint $table) {
            $table->integer('idcatalogo_firma')->unsigned()->autoIncrement()->primary();
            $table->string('descripcion', 45)->nullable();
            $table->string('observaciones', 45)->nullable();
            $table->integer('estatus')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogo_firma');
    }
};
