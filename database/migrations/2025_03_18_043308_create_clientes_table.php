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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('CLICOD', 10)->unique();
            $table->string('CLINOM', 100);
            $table->string('CLICD', 50);
            $table->string('CLIPAR1', 10);
            $table->string('CLIPAR7', 10);
            $table->string('CLISUCURSAL', 50);
            $table->string('CLINOM2', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
