<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reporte_faltante', function (Blueprint $table) {
            // Verificamos que la columna exista
            if (Schema::hasColumn('reporte_faltante', 'motivo_faltante_id')) {

                // Si quieres mantener los datos antiguos, puedes mapearlos a los ids del catálogo.
                // Por simplicidad, en este ejemplo pondremos NULL a los registros existentes para no romper la FK
                DB::table('reporte_faltante')->update(['motivo_faltante_id' => 1]);

                // Creamos la foreign key
                $table->foreign('motivo_faltante_id')
                      ->references('id')
                      ->on('catalogo_motivo_faltante')
                      ->onDelete('restrict')
                      ->onUpdate('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reporte_faltante', function (Blueprint $table) {
            if (Schema::hasColumn('reporte_faltante', 'motivo_faltante_id')) {
                $table->dropForeign(['motivo_faltante_id']);
            }
        });
    }
};
