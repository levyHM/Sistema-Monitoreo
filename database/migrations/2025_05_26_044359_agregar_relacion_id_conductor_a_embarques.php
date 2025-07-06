<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
{
    Schema::table('embarques', function (Blueprint $table) {
        $table->unsignedBigInteger('id_conductor')->change();
        $table->foreign('id_conductor')->references('id')->on('conductores')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('embarques', function (Blueprint $table) {
        $table->dropForeign(['id_conductor']);
    });
}
};
