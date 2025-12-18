<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reporte_faltante', function (Blueprint $table) {
            $table->foreign('catalogo_reporte_faltante_tipo_id')
                  ->references('id')
                  ->on('catalogo_reporte_faltante_tipo')
                  ->onUpdate('no action')
                  ->onDelete('no action');
        });
    }

    public function down(): void
    {
        Schema::table('reporte_faltante', function (Blueprint $table) {
            $table->dropForeign(['catalogo_reporte_faltante_tipo_id']);
        });
    }
};
