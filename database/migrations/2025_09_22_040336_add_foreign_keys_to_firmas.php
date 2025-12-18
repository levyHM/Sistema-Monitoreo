<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reporte_soluciones_clientes', function (Blueprint $table) {
            // Aseguramos que los campos tengan el tipo correcto
            $table->unsignedBigInteger('firma_soluciones')->nullable()->change();
            $table->unsignedBigInteger('firma_credito')->nullable()->change();

            // Agregamos las claves foráneas
            $table->foreign('firma_soluciones')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');

            $table->foreign('firma_credito')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('reporte_soluciones_clientes', function (Blueprint $table) {
            $table->dropForeign(['firma_cliente']);
            $table->dropForeign(['firma_soluciones']);
            $table->dropForeign(['firma_credito']);
        });
    }
};
