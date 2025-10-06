<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reporte_soluciones_clientes', function (Blueprint $table) {
            $table->renameColumn('garantia', 'catalogo_tipo_id');
        });
    }

    public function down(): void
    {
        Schema::table('reporte_soluciones_clientes', function (Blueprint $table) {
            $table->renameColumn('tipo_garantia_id', 'garantia');
        });
    }
};
