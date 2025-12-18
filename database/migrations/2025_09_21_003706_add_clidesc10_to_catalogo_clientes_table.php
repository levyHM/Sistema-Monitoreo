<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('catalogo_clientes', function (Blueprint $table) {
            $table->string('clidesc10', 100)->nullable()
                  ->after('clipar1'); // justo después de clipar1
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catalogo_clientes', function (Blueprint $table) {
            $table->dropColumn('clidesc10');
        });
    }
};
