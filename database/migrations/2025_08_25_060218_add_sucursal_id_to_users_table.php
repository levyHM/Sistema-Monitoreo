<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 1️⃣ Crear la columna en la posición deseada
            $table->unsignedBigInteger('catalogo_sucursales_id')
                ->nullable()
                ->after('password');

            // 2️⃣ Agregar la foreign key
            $table->foreign('catalogo_sucursales_id')
                ->references('id')
                ->on('catalogo_sucursales')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['catalogo_sucursales_id']);
            $table->dropColumn('catalogo_sucursales_id');
        });
    }
};
