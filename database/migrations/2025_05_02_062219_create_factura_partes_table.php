<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('factura_partes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('embarques_id'); // FK hacia embarques.id
            $table->string('factura')->nullable();
            $table->integer('parte_num')->nullable();
            $table->tinyInteger('estado_validacion')->nullable(); // 0: no validado, 1: validado
            $table->string('observaciones', 45)->nullable();
            $table->timestamps();

            // Clave foránea
            $table->foreign('embarques_id')->references('id')->on('embarques')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factura_partes');
    }
};
