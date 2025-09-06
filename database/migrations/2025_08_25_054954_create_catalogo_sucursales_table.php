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
        Schema::create('catalogo_sucursales', function (Blueprint $table) {
            $table->id();
            $table->enum('codigo', ['F', 'PV', 'PO'])->unique(); // Código de sucursal
            $table->string('nombre', 45); // Nombre completo de la sucursal
            $table->string('estatus'); // Estatus
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogo_sucursales');
    }
};
