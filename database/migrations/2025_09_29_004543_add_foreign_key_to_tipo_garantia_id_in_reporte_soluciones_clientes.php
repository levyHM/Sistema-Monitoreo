<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reporte_soluciones_clientes', function (Blueprint $table) {
            $table->foreign('catalogo_tipo_id', 'fk_reporte_soluciones_clientes_catalogo_tipo')
                ->references('id')
                ->on('catalogo_tipo')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('reporte_soluciones_clientes', function (Blueprint $table) {
            $table->dropForeign('fk_reporte_soluciones_clientes_catalogo_tipo');
        });
    }
};
