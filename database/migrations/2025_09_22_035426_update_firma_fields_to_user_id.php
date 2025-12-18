<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reporte_soluciones_clientes', function (Blueprint $table) {
            $table->unsignedBigInteger('firma_soluciones')->nullable()->change();
            $table->unsignedBigInteger('firma_credito')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('reporte_soluciones_clientes', function (Blueprint $table) {
            $table->integer('firma_soluciones')->nullable()->change();
            $table->integer('firma_credito')->nullable()->change();
        });
    }
};
