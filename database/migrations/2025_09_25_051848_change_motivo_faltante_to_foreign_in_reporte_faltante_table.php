<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reporte_faltante', function (Blueprint $table) {
            // Eliminamos la columna antigua si existe
            if (Schema::hasColumn('reporte_faltante', 'motivo_faltante')) {
                $table->dropColumn('motivo_faltante');
            }

            // Creamos la nueva columna sin la foreign key aún
            if (!Schema::hasColumn('reporte_faltante', 'motivo_faltante_id')) {
                $table->unsignedBigInteger('motivo_faltante_id')->after('solucion');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reporte_faltante', function (Blueprint $table) {
            if (Schema::hasColumn('reporte_faltante', 'motivo_faltante_id')) {
                $table->dropColumn('motivo_faltante_id');
            }

            if (!Schema::hasColumn('reporte_faltante', 'motivo_faltante')) {
                $table->string('motivo_faltante', 70)->after('solucion');
            }
        });
    }
};
