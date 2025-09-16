<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reporte_falta', function (Blueprint $table) {
            $table->renameColumn('pendiente', 'empaco');
        });
    }

    public function down(): void
    {
        Schema::table('reporte_falta', function (Blueprint $table) {
            $table->renameColumn('empaco', 'pendiente');
        });
    }
};
