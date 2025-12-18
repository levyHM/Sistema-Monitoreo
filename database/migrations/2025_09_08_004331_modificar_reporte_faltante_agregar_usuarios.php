<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reporte_faltante', function (Blueprint $table) {
            // Eliminar campos antiguos si existen
            if (Schema::hasColumn('reporte_faltante', 'folio')) {
                $table->dropColumn('folio');
            }
            if (Schema::hasColumn('reporte_faltante', 'autorizo')) {
                $table->dropColumn('autorizo');
            }

            // Agregar relación con usuario que registró la orden
            if (!Schema::hasColumn('reporte_faltante', 'user_id_registro')) {
                $table->foreignId('user_id_registro')
                    ->nullable()
                    ->after('nc_servicio')
                    ->constrained('users', 'id')
                    ->onUpdate('no action')
                    ->onDelete('no action');
            }

            // Agregar relación con usuario que autorizó la orden
            if (!Schema::hasColumn('reporte_faltante', 'user_id_autorizo')) {
                $table->foreignId('user_id_autorizo')
                    ->nullable()
                    ->after('user_id_registro')
                    ->constrained('users', 'id')
                    ->onUpdate('no action')
                    ->onDelete('no action');
            }

            // Agregar campo observaciones solo si no existe
            if (!Schema::hasColumn('reporte_faltante', 'observaciones')) {
                $table->string('observaciones', 255)
                    ->nullable()
                    ->after('user_id_autorizo');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reporte_faltante', function (Blueprint $table) {
            // Revertir relaciones y campo observaciones
            if (Schema::hasColumn('reporte_faltante', 'observaciones')) {
                $table->dropColumn('observaciones');
            }

            if (Schema::hasColumn('reporte_faltante', 'user_id_autorizo')) {
                $table->dropForeign(['user_id_autorizo']);
                $table->dropColumn('user_id_autorizo');
            }

            if (Schema::hasColumn('reporte_faltante', 'user_id_registro')) {
                $table->dropForeign(['user_id_registro']);
                $table->dropColumn('user_id_registro');
            }

            // Restaurar campos antiguos
            if (!Schema::hasColumn('reporte_faltante', 'folio')) {
                $table->string('folio', 45)->nullable()->after('nc_servicio');
            }
            if (!Schema::hasColumn('reporte_faltante', 'autorizo')) {
                $table->string('autorizo', 45)->nullable()->after('folio');
            }
        });
    }
};
