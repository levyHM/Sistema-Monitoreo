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
        Schema::table('lista_soluciones_clientes', function (Blueprint $table) {

            // Fecha en que se operó
            $table->date('fecha_operador')->nullable()->after('total');

            // Campo de texto para la ruta (sin relación)
            $table->string('ruta')->nullable()->after('fecha_operador');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lista_soluciones_clientes', function (Blueprint $table) {

            // Eliminar columnas agregadas
            $table->dropColumn(['fecha_operador', 'ruta']);
        });
    }
};
