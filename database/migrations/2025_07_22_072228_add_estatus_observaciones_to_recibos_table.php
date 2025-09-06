<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recibos', function (Blueprint $table) {
            $table->enum('estatus', ['activo', 'cancelado'])->default('activo')->after('fecha');
            $table->string('observaciones')->nullable()->after('estatus');
        });
    }

    public function down(): void
    {
        Schema::table('recibos', function (Blueprint $table) {
            $table->dropColumn('estatus');
            $table->dropColumn('observaciones');
        });
    }
};
