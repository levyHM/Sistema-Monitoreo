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
        Schema::create('catalogo_provedores', function (Blueprint $table) {
            $table->integer('idcatalogo_provedores')->unsigned()->autoIncrement()->primary();
            $table->string('prvcod', 45)->nullable();
            $table->string('prvnom', 250)->nullable();
            $table->integer('estatus')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogo_provedores');
    }
};
