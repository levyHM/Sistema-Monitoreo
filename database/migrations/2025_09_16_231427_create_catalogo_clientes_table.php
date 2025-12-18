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
        Schema::create('catalogo_clientes', function (Blueprint $table) {
            $table->id('idcatalogo_clientes'); // Clave primaria
            $table->string('clicod', 45)->unique();
            $table->string('clinom', 150)->nullable();      // Nombre completo del cliente
            $table->string('clipar1', 45)->nullable();      // Clasificación o segmento
            $table->string('observaciones', 100)->nullable(); // Notas internas
            $table->integer('estatus')->nullable();         // Estado lógico del cliente
            $table->timestamps(); // Para trazabilidad
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogo_clientes');
    }
};
