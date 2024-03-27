<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('cursoCertificados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idCurso');
            $table->json('data'); // Columna para almacenar datos en formato JSON

            // Definir clave foránea con la tabla 'cursos'
            $table->foreign('idCurso')->references('id')->on('cursos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('cursoCertificados');
    }
};
