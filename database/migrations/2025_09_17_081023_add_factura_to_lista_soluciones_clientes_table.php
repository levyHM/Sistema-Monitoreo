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
            $table->string('factura', 70)->nullable()->after('catalogo_soluciones_clientes_idCatalogoSolucionesClientes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lista_soluciones_clientes', function (Blueprint $table) {
            $table->dropColumn('factura');
        });
    }
};
