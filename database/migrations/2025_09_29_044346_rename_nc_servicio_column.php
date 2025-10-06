<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reporte_faltante', function (Blueprint $table) {
            $table->renameColumn('nc_servicio', 'catalogo_reporte_faltante_tipo_id');
        });
    }

    public function down(): void
    {
        Schema::table('reporte_faltante', function (Blueprint $table) {
            $table->renameColumn('catalogo_reporte_faltante_tipo_id', 'nc_servicio');
        });
    }
};
