<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('evidencias_lista_soluciones', function (Blueprint $table) {

            $table->id('id'); // puedes cambiar el nombre si quieres
            $table->unsignedBigInteger('lista_soluciones_clientes_id'); // FK correcta
            $table->string('archivo', 255);
            $table->integer("estatus")->default(1);
            $table->timestamps();

            // Índice
            $table->index('lista_soluciones_clientes_id', 'fk_evidencia_lista_soluciones_idx');

            // Relación con la tabla principal
            $table->foreign(
                'lista_soluciones_clientes_id',
                'fk_evidencia_lista_soluciones'
            )
                ->references('idlista_soluciones_clientes')   // PK REAL de tu tabla
                ->on('lista_soluciones_clientes')
                ->onDelete('cascade') // si se elimina la solución, se eliminan las evidencias
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evidencias_lista_soluciones');
    }
};
