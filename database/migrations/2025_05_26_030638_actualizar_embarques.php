<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('embarques', function (Blueprint $table) {
            $table->renameColumn('ID_OPERADOR', 'id_conductor');
        });
    }

    public function down()
    {
        Schema::table('embarques', function (Blueprint $table) {
            $table->renameColumn('id_conductor', 'ID_OPERADOR'); // Reversión
        });
    }
};
